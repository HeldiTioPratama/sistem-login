<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-3 text-gray-800">Role Access</h1>

    <div class="row">
        <div class="col-lg-8">

            <!-- pesan error atau berhasil -->
            <?= $this->session->flashdata('message'); ?>

            <h5 class="btn btn-secondary mb-3">Role : <?= $role['role'] ?></h5>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Menu</th>
                        <th scope="col">Access</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    foreach ($menu as $m) : ?>
                        <tr>
                            <th scope="row"><?= $i ?></th>
                            <td><?= $m['menu'] ?></td>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" <?= role_access($role['id'], $m['id']) ?> data-role="<?= $role['id'] ?>" data-menu="<?= $m['id'] ?>">
                                </div>
                            </td>
                            <?php $i++ ?>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <a href="<?= base_url('admin/role') ?>" class="btn btn-outline-primary mb-3 col-2"> Back </a>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->