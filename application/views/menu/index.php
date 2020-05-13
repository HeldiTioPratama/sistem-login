<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Menu Management</h1>

    <div class="row">
        <div class="col-lg-8">
            <a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addNewMenu">Add New Menu</a>

            <!-- pesan error atau berhasil -->
            <?= form_error('menu', '<div class="alert alert-danger" role="alert">', '</div>'); ?>
            <?= $this->session->flashdata('message'); ?>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Menu</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    foreach ($menu as $m) : ?>
                        <tr>
                            <th scope="row"><?= $i ?></th>
                            <td><?= $m['menu'] ?></td>
                            <td>
                                <a href="" class="badge badge-info" data-toggle="modal" data-target="#editMenu">Edit</a>
                                <a href="<?= base_url('menu/delete') ?>/<?= $m['id'] ?>" class="badge badge-danger">Hapus</a>
                            </td>
                            <?php $i++ ?>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<!-- Modal Add New Menu -->

<div class="modal fade" id="addNewMenu" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addNewMenuLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNewMenuLabel">Add New Menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('menu') ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="menu">Menu</label>
                        <input type="text" class="form-control" id="menu" name="menu" placeholder="Menu name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- modal edit menu -->

<div class="modal fade" id="editMenu" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="editMenuLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMenuLabel">Edit Menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('menu/edit') ?>/<?= $m['id'] ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="menu">Menu</label>
                        <input type="text" class="form-control" id="menu" name="editMenu" placeholder="New name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>