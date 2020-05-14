<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    // untuk methdoe dafault Auth dan sekaligus halaman login
    public function index()
    {
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
                    Your account is not active, please activate your account via email link
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
            'required|trim|matches[password2]|min_length[3]',
            [
                'matches' => "Password don't match!",
                "min_length" => "Password min 3 character"
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

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Thanks for your registration, Please login!
            </div>');
            redirect('auth');
        }
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
