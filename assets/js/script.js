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