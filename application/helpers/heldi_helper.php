<?php

function check_access()
{
    $ci = get_instance();

    if (!$ci->session->userdata('email')) {
        redirect('auth');
    } else {
        $roleId = $ci->session->userdata('role_id');
        $menu = $ci->uri->segment(1);
        $queryMenu = $ci->db->get_where('user_menu', ['menu' => $menu])->row_array();
        $menuId = $queryMenu['id'];

        $userAccess = $ci->db->get_where(
            'user_access_menu',
            [
                'role_id' => $roleId,
                'menu_id' => $menuId
            ]
        );


        // dicari apakah ada baris yang cocok saat query userAccess, jika user masukan url dan gada maka gaboleh
        if ($userAccess->num_rows() < 1) {
            redirect('auth/blocked');
        } else {
        }
    }
}
