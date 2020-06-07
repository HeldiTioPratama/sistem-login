<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
    public function inputUser()
    {
        $data = [
            'name' => htmlspecialchars($this->input->post('name', true)),
            'email' => htmlspecialchars($this->input->post('email', true)),
            'image' => 'default.jpg',
            'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
            'role_id' => 2,
            'is_active' => 0,
            'create_date' => time()
        ];

        $this->db->insert('user', $data);
    }

    public function createdToken($token)
    {
        $email = $this->input->post('email');
        $data = [
            'email' => $email,
            'token' => $token,
            'date_created' => time()
        ];

        $this->db->insert('user_token', $data);
    }
}
