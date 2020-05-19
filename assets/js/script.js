$(function () {

    // untuk ajax role access
    $('.form-check-input').on('click', function () {

        const menuId = $(this).data('menu');
        const roleId = $(this).data('role');

        console.log(menuId)
        $.ajax({
            url: "http://localhost/login/admin/changeAccess",
            type: "post",
            data: {
                menuId: menuId,
                roleId: roleId
            },
            success: () => {
                document.location.href = `http://localhost/login/admin/roleaccess/${roleId}`
            }
        });
    });

    // untuk ajak ubah menu modal

    $('#addNewMenu').on('click', function () {
        $('#labelModal').html('Add new menu')
        $('.modal-footer button[type=submit]').html('Add menu');
        $('.modal-body form').attr('action', 'http://localhost/login/menu');
        $('.modal-footer button[type=submit]').removeClass('btn btn-warning');
        $('.modal-footer button[type=submit]').addClass('btn btn-primary');
        $('.modal-body form')[0].reset();


    });



    $('.editmenu').on('click', function () {
        $('#labelModal').html('Edit menu')
        $('.modal-footer button[type=submit]').html('Edit menu');
        $('.modal-body form').attr('action', 'http://localhost/login/menu/editMenu');
        $('.modal-footer button[type=submit]').addClass('btn btn-warning');

        const menuId = $(this).data('id');

        $.ajax({
            url: "http://localhost/login/menu/getMenu",
            data: { id: menuId },
            methode: 'post',
            dataType: 'json',
            success: function (data) {

                $('#id').val(data.id);
                $('#menu').val(data.menu);
            }
        });
    });


    // untuk ajak ubah submenu modal

    $('.new-sub-menu').on('click', function () {
        $('#labelModal').html('Add new SubMenu')
        $('.modal-footer button[type=submit]').html('Add new SubMenu');
        $('.modal-body form').attr('action', 'http://localhost/login/menu/subMenu');
        $('.modal-footer button[type=submit]').removeClass('btn btn-warning');
        $('.modal-footer button[type=submit]').addClass('btn btn-primary');
        $('.modal-body form')[0].reset();


    });



    $('.edit-subMenu').on('click', function () {
        $('#labelModal').html('Edit SubMenu')
        $('.modal-footer button[type=submit]').html('Edit SubMenu');
        $('.modal-body form').attr('action', 'http://localhost/login/menu/editSubMenu');
        $('.modal-footer button[type=submit]').addClass('btn btn-warning');

        const subMenuId = $(this).data('id');

        $.ajax({
            url: "http://localhost/login/menu/getSubMenu",
            data: { id: subMenuId },
            methode: 'post',
            dataType: 'json',
            success: function (data) {

                $('#id').val(data.id);
                $('#title').val(data.title);
                $('#menu_id').val(data.menu_id);
                $('#url').val(data.url);
                $('#icon').val(data.icon);
                $('#is_active').val(data.is_active);
            }
        });
    });

    $('.custom-file-input').on('change', function () {
        let fileName = $(this).val().split('\\').pop();
        $('.custom-file-label').html(fileName);
    })

})