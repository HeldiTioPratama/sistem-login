<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

    <?= form_open_multipart('profile/edit'); ?>
    <div class="form-group row">
        <label for="email" class="col-sm-2 col-form-label">Email</label>
        <div class="col-sm-6">
            <input readonly class="form-control-plaintext" id="email" name="email" value="<?= $user['email'] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="name" class="col-sm-2 col-form-label">Full name</label>
        <div class="col-sm-6">
            <input type="text" class="form-control" name="name" id="name" value="<?= $user['name'] ?>">
            <?= form_error('name', '<small class="text-danger pl-2" >', '</small>') ?>
        </div>
    </div>
    <div class="form-group row">
        <label for="name" class="col-sm-2 col-form-label">Picture</label>
        <div class="col-sm-6">
            <div class="row">
                <div class="col-sm-3">
                    <img src="<?= base_url('assets/img/profile/') . $user['image'] ?>" alt="profile" class="img-thumbnail">
                </div>
                <div class="col-sm-9">
                    <div class="custom-file overflow-hidden">
                        <input type="file" class="custom-file-input" id="file" name="image">
                        <label class="custom-file-label" for="file">Choose file</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row justify-content-end">
        <div class="col-sm-10">
            <button type="submit" class="btn btn-primary mt-3">Edit</button>
        </div>
    </div>
    </form>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->