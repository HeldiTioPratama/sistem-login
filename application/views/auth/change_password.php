<body class="bg-gradient-primary">
    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-lg-6 col-md-8 col-sm-12">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-md-10 col-lg-10 col-sm-10 col- mx-auto">
                                <div class="pl-3 pr-3 pt-5 pb-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Change password</h1>
                                        <h1 class="h5 text-gray-900 mb-4"><?= $email ?></h1>
                                    </div>
                                    <?= $this->session->flashdata('message') ?>
                                    <form class="user" method="post" action="<?= base_url('auth/changepassword') ?>">
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" id="password1" name="password1" placeholder="Enter New Password...">
                                            <?= form_error('password1', '<small class="text-danger pl-3 mt-2">', '</small>') ?>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" id="password2" name="password2" placeholder="Repeat Password...">
                                            <?= form_error('password2', '<small class="text-danger pl-3 mt-2">', '</small>') ?>
                                        </div>
                                        <button type="submit" name="button" class="btn btn-primary btn-user btn-block">
                                            Change Password
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>