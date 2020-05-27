<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    // untuk methdoe dafault Auth dan sekaligus halaman login
    public function index()
    {
        // agar yang sudah login tidak bisa kehalaman login kecuali logout dahulu
        if ($this->session->userdata('email')) {
            $role = $this->db->get_where('user_role', ['id' => $this->session->userdata('role_id')])->row_array();
            redirect($role['role']);
        }
        // cek validasi form sesuai rules
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('password', 'Email', 'required|trim');

        // jika validasi gagal
        if ($this->form_validation->run() == false) {
            $data['title'] = 'Login';
            $this->load->view('template/header', $data);
            $this->load->view('auth/login');
            $this->load->view('template/footer');

            // jika validasi berhasil dan masuk methode login
        } else {
            $this->_login();
        }
    }

    // methode login
    private function _login()
    {
        // mengambil isi dari form melalui metode post
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        // mengambil isi database sesuai email yang diinput pada form
        $user = $this->db->get_where('user', ['email' => $email])->row_array();

        // jika return value nya ada isinya atau user tidak kosong atau ada data dalam database
        if ($user) {

            // jika akun user yang mau login sudah aktif
            if ($user['is_active'] == 1) {

                // jika atau untuk cek passwordnya sama atau tidak
                if (password_verify($password, $user['password'])) {

                    // jika sama makan bikin seesion dibawah dan dikirim pada redirect
                    $data = [
                        'email' => $user['email'],
                        'role_id' => $user['role_id']
                    ];

                    // mengaktifkan seesion
                    $this->session->set_userdata($data);

                    if ($user['role_id'] == 1) {
                        redirect('admin');
                    } else {
                        redirect('member');
                    }

                    // jika password tidak sama tampilkan pesan kesalahan menggunakan flasdata
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                    Your password is incorrect!
                     </div>');
                    redirect('auth');
                }

                // jika email dan akunnya belum di aktivasi
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">
                    Your account is not active, please activate your account via email link, if you don\'t receive the email <a href="' . base_url('auth/resendtoken') . '"> Klik this link to resend email activation account</a>
                 </div>');
                redirect('auth');
            }

            // jika email belum terfdaftar
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                Your email has not been registered, please register
            </div>');
            redirect('auth');
        }
    }

    // untuk registrasi
    public function registration()
    {
        // agar yang sudah login tidak bisa kehalaman restrasi kecuali logout dahulu
        if ($this->session->userdata('email')) {
            $role = $this->db->get_where('user_role', ['id' => $this->session->userdata('role_id')])->row_array();
            redirect($role['role']);
        }
        // cek validasi form sesuai rules
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules(
            'email',
            'Email',
            'required|trim|valid_email|is_unique[user.email]',
            [
                "is_unique" => "This email already exists"
            ]
        );
        $this->form_validation->set_rules(
            'password1',
            'Password',
            'required|trim|matches[password2]|min_length[6]',
            [
                'matches' => "Password don't match!",
                "min_length" => "Password min 6 character"
            ]
        );
        $this->form_validation->set_rules('password2', 'Password', 'trim|matches[password1]');

        // jika validasi gagal
        if ($this->form_validation->run() == false) {
            $data['title'] = 'User Registration';
            $this->load->view('template/header', $data);
            $this->load->view('auth/registration');
            $this->load->view('template/footer');

            // jika validasi berhasil dan masukan data pada database
        } else {
            $this->User_model->inputUser();

            // siapkan token
            $token = base64_encode(random_bytes(32));
            $this->User_model->createdToken($token);

            // kirim email
            $this->_sendEmail($token, 'verify');

            // tampilkan pesan 
            $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">
            Thanks for your registration, Please activate your account!, if you don\'t receive the email <a href="' . base_url('auth/resendtoken') . '"> Klik this link to resend email activation account</a>
            </div>');
            redirect('auth');
        }
    }

    // configurasi kirim email
    private function _sendEmail($token, $type)
    {

        // ini diambil dari input user registrasi
        $email = $this->input->post('email');
        $name = $this->input->post('name');

        // konfigurasi smtp email googlemail
        $config = [
            'protocol'  => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_user' => 'sistemperpus@gmail.com',
            'smtp_pass' => 'sistemperpus99',
            'smtp_port' => 465,
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'newline'   => "\r\n"

        ];

        // inisialisasi email
        $this->email->initialize($config);

        // untuk load library email
        $this->load->library('email', $config);

        // untuk email pengirim dan email tujuan
        $this->email->from('sistemperpus@gmail.com', 'Sistem Perpustakaan');
        $this->email->to($email);

        // untuk cek type type diambil dari argument function
        if ($type == 'verify') {
            // subjek dan pesan email  urlencode untuk mengkonvert karakter atau string ke dalam bentuk format karakter URL yang valid
            $this->email->subject('Account verification');
            $this->email->message('
            <h3>Halo ' . $name . '</h3>
            <p>Terimakasih telah mendaftar akun diperpustakaan online</p>
            <p>Silahkan klik tautan atau link berikut untuk mengaktifkan akun anda</p>
            <a href="' . base_url() . 'Auth/verify?email=' . $email . '&token=' . urlencode($token) . '">Klik untuk aktifkan akun</a>
        ');
        }

        // kondisi dimana untuk menampikan error jika ada kesalahan
        if ($this->email->send()) {
            return true;
        } else {
            echo $this->email->print_debugger();
            die;
        }
    }

    // function untuk verify token pada email
    public function verify()
    {
        // ini diambil dari url link pada email
        $email = $this->input->get('email');
        $token = $this->input->get('token');

        // untuk ambil data user yang memiliki email apakah sudah ada atau belum
        $user_activation_email = $this->db->get_where('user_activation', ['email' => $email])->row_array();

        if ($user_activation_email) {
            // untuk mengambil token apakah pada table
            $user_activation_token = $this->db->get_where('user_activation', ['token' => $token])->row_array();

            if ($user_activation_token) {
                // untuk cek apakah token expired atau belum
                if (time() - $user_activation_token['date_created'] < (60 * 3)) {

                    // jika belum expired update data yang emailnya sudah diactivated
                    $this->db->set('is_active', 1);
                    $this->db->where('email', $email);
                    $this->db->update('user');

                    // hapus semua data token dan emailnya di db jika sudah diaktivasi
                    $this->db->delete('user_activation', ['email' => $email]);

                    // tampilkan pesan
                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
                    Activation account success, ' . $email . ' has been activaved, please login
                    </div>');

                    redirect('auth');
                } else {
                    // tampilkan pesan token expired
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                    Activation account failed!, token expaired <a href="' . base_url('auth/resendToken') . '"> Klik this link to resend email activation account</a>
                    </div>');

                    redirect('auth');
                }
            } else {
                // tampilkan pesan token salah
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            Activation account failed!, token wrong! please check your email and click link
            </div>');

                redirect('auth');
            }
        } else {
            // tampilkan pesan email salah
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            Activation account failed!, please check your email is valid
            </div>');

            redirect('auth');
        }
    }

    // ini function untuk fitur resend token aktivasi
    public function resendToken()
    {
        echo 'okeee';
    }

    // untuk logout
    public function logout()
    {
        // menghapus session pada halaman web client
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('role_id');

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
        You have been logged out, Thanks you
        </div>');

        redirect('auth');
    }

    public function blocked()
    {
        $data['title'] = 'Access Forbidden';

        if ($this->session->userdata['role_id'] == 1) {
            $data['redirect'] = 'admin';
        } else {
            $data['redirect'] = 'member';
        }


        $this->load->view('template/header', $data);
        $this->load->view('auth/blocked', $data);
        $this->load->view('template/footer');
    }
}
