<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        check_access();
    }


    public function index()
    {
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['title'] = 'My Profile';

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('template/topbar', $data);
        $this->load->view('profile/index', $data);
        $this->load->view('template/copyright', $data);
        $this->load->view('template/footer');
    }


    public function edit()
    {
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['title'] = 'Edit Profile';

        $this->form_validation->set_rules('name', 'Full name', 'required|trim');
        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('profile/edit', $data);
            $this->load->view('template/copyright', $data);
            $this->load->view('template/footer');
        } else {
            $name = $this->input->post('name');
            $email = $this->input->post('email');
            $uploadImage = $_FILES['image']['name'];

            if ($uploadImage) {
                $config['upload_path'] = './assets/img/profile/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size']     = '2048';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {
                    $oldImage = $data['user']['image'];
                    if ($oldImage != 'default.jpg') {
                        unlink(FCPATH . 'assets/img/profile/' . $oldImage);
                    }
                    $newImage = $this->upload->data('file_name');
                    $this->db->set('image', $newImage);
                } else {
                    echo $this->upload->display_errors();
                }
            }
            $this->db->set('name', $name);
            $this->db->where('email', $email);
            $this->db->update('user');

            $this->session->set_flashdata('editProfile', '<div class="alert alert-success" role="alert">
            Edit profile success
            </div>');
            redirect('profile');
        }
    }

    public function changePassword()
    {
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['title'] = 'Change Password';

        // untuk rules new password
        $this->form_validation->set_rules('current_password', 'Current Password', 'required|trim');
        $this->form_validation->set_rules(
            'new_password1',
            'New Password',
            'required|trim|min_length[6]|matches[new_password2]'
        );
        $this->form_validation->set_rules(
            'new_password2',
            'Confirm New Password',
            'required|trim|matches[new_password2]'
        );

        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('profile/changepassword', $data);
            $this->load->view('template/copyright', $data);
            $this->load->view('template/footer');
        } else {
            $currentPassword = $this->input->post('current_password');
            $newPassword = $this->input->post('new_password1');

            // cek password lama sama dengan di hash db
            if (password_verify($currentPassword, $data['user']['password'])) {

                //cek apakah password lama sama dengan yang baru
                if ($newPassword != $currentPassword) {
                    $data = [
                        'password' => password_hash($newPassword, PASSWORD_DEFAULT)
                    ];

                    $this->db->update('user', $data, ['email' => $this->session->userdata('email')]);
                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
                    Password success change
                    </div>');
                    redirect('profile/changepassword');
                }
                // jika password baru sama dengan password lama
                else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                    New passwords cannot be the same current passowrd
                    </div>');
                    redirect('profile/changepassword');
                }
            }
            // jika password lama tidak sama dengan hash db
            else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                Wrong current password
                </div>');
                redirect('profile/changepassword');
            }
        }
    }
}
