<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends CI_Controller
{

    public function index()
    {
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['title'] = 'Menu Management';

        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->form_validation->set_rules('menu', 'Menu', 'required');
        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('template/copyright', $data);
            $this->load->view('template/footer');
        } else {
            $this->MenuManagement_model->addMenu();
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Add menu success
            </div>');
            redirect('menu');
        }
    }

    public function delete($id)
    {
        $this->MenuManagement_model->delete($id);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
        Delete menu success
        </div>');
        redirect('menu');
    }

    public function edit($id)
    {
        $this->MenuManagement_model->edit($id);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
        Edit menu success
        </div>');
        redirect('menu');
    }
}
