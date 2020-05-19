<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        check_access();
    }


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


    public function getMenu()
    {
        // echo json_encode($this->db->get_where('user_menu', ['id' => $this->input->post('id')])->row_array());
        echo json_encode($this->db->get_where('user_menu', ['id' => $this->input->post_get('id')])->row_array());
    }

    public function editMenu()
    {
        $data = ['menu' => $this->input->post('menu')];

        $this->db->update('user_menu', $data, ['id' => $this->input->post('id')]);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
        Edit menu success
        </div>');
        redirect('menu');
    }


    public function deleteMenu($id)
    {
        $this->db->delete('user_menu', ['id' => $id]);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
        Delete menu success
        </div>');
        redirect('menu');
    }


    public function subMenu()
    {
        //untuk profile nanti
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        //untuk heading
        $data['title'] = 'SubMenu Management';

        // ambil data user_menu
        $data['menu'] = $this->MenuManagement_model->getMenu();
        //ambil data user_sub_menu
        $data['subMenu'] = $this->MenuManagement_model->subMenu();

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('menu_id', 'Menu', 'required');
        $this->form_validation->set_rules('url', 'Url', 'required');
        $this->form_validation->set_rules('icon', 'Icon', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar', $data);
            $this->load->view('template/topbar', $data);
            $this->load->view('menu/submenu', $data);
            $this->load->view('template/copyright', $data);
            $this->load->view('template/footer');
        } else {
            $this->MenuManagement_model->addSubMenu();

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Add SubMenu success
            </div>');
            redirect('menu/subMenu');
        }
    }

    public function deleteSubMenu($id)
    {
        $this->db->delete('user_sub_menu', ['id' => $id]);

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Delete SubMenu success
            </div>');
        redirect('menu/subMenu');
    }

    public function getSubMenu()
    {
        echo json_encode($this->db->get_where('user_sub_menu', ['id' => $this->input->post_get('id')])->row_array());
    }

    public function editSubMenu()
    {
        $this->MenuManagement_model->editSubMenu();
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
        Edit menu success
        </div>');
        redirect('menu/subMenu');
    }
}
