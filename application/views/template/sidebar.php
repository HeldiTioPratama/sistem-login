<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <?= $roleId = $this->session->userdata['role_id'];
            if ($roleId == 1) : ?>
                <!-- Sidebar - Brand -->
                <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('admin') ?>">
                    <div class="sidebar-brand-icon rotate-n-15">
                        <i class="fas fa-laugh-wink"></i>
                    </div>
                    <div class="sidebar-brand-text mx-3">Administrator</div>
                </a>
            <?php else : ?>
                <!-- Sidebar - Brand -->
                <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('member') ?>">
                    <div class="sidebar-brand-icon rotate-n-15">
                        <i class="fas fa-laugh-wink"></i>
                    </div>
                    <div class="sidebar-brand-text mx-3">Member</div>
                </a>
            <?php endif ?>
            <!-- Query untuk menampilkan menu -->
            <?php
            $queryMenu = "
                SELECT `user_menu`.`id`, `menu`
                FROM `user_menu` JOIN `user_access_menu`
                ON `user_menu`.`id` = `user_access_menu`.`menu_id`
                WHERE `user_access_menu`.`role_id` = $roleId
                ORDER BY `user_access_menu`.`menu_id` ASC
            ";
            $menu = $this->db->query($queryMenu)->result_array();
            ?>

            <!-- Home untuk user -->
            <?php if ($roleId == 2) : ?>

            <?php endif ?>


            <!-- LOOPING menu -->
            <?php foreach ($menu as $m) : ?>
                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Heading -->
                <?php if ($m['menu'] == 'Member') : ?>
                    <div class="sidebar-heading">
                        Fitur
                    </div>
                <?php elseif ($m['menu'] == 'Admin') : ?>
                    <div class="sidebar-heading">
                        Core
                    </div>
                <?php else : ?>
                    <div class="sidebar-heading">
                        <?= $m['menu'] ?>
                    </div>
                <?php endif ?>

                <!-- Query Sub-Menu -->
                <?php $subMenu = $this->db->get_where('user_sub_menu', ['menu_id' => $m["id"]])->result_array();
                foreach ($subMenu as $sub) : ?>
                    <?php if ($title == $sub['title']) : ?>
                        <!-- Nav Item - Dashboard -->
                        <li class="nav-item active">
                        <?php else : ?>
                        <li class="nav-item">
                        <?php endif ?>
                        <a class="nav-link" href="<?= $sub['url'] ?>">
                            <i class="<?= $sub['icon'] ?>"></i>
                            <span><?= $sub['title'] ?></span> </a> </li>
                    <?php endforeach ?>
                <?php endforeach ?>
                <!-- Divider -->
                <hr class="sidebar-divider d-none d-md-block">
                <!-- Nav Item - Tables -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('auth/logout') ?>" data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-sign-out-alt fa-fw"></i>
                        <span>Logout</span></a>
                </li>

                <!-- Divider -->
                <hr class="sidebar-divider d-none d-md-block">

                <!-- Sidebar Toggler (Sidebar) -->
                <div class="text-center d-none d-md-inline">
                    <button class="rounded-circle border-0" id="sidebarToggle"></button>
                </div>

        </ul>
        <!-- End of Sidebar -->