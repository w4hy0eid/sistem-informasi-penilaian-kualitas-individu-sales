let statusForm = '';

$(document).ready(function () {
    $('#userTable').DataTable();
});

function newUserBtn() {
    $("#exampleModalLabel").text("Create User");
    $('#formCreateUser')[0].reset();
    statusForm = 'create';
    $("#alertMessage").empty();
    $("#input-password").show();
}

function updateUserBtn(idUser) {
    statusForm = 'update';
    $("#alertMessage").empty();
    $.getJSON(baseUrl + '/user/' + idUser, (data) => {
        $("#exampleModalLabel").text("Update User");
        $("[name='id']").val(data.data.id);

        $("[name='email']").val(data.data.email);
        $("[name='nik']").val(data.data.nik);
        $("[name='name']").val(data.data.name);
        $("[name='tr']").val(data.data.tr);
        $("[name='level']").val(data.data.level);
        $("[name='segmen']").val(data.data.segmen);

        $("#input-password").hide();
    });
}

function deleteUser(idUser) {
    if(confirm("hapus?")) {
        $.ajax({
            url: baseUrl + `/action/user/delete/${idUser}`,
            type: "delete",
            dataType: "json",
            data: {
                "_token": tokenCsrf
            },
            success: function (data) {
                if (data.valid) {
                    alert(data.message);
                } else {
                    alert(data.message);
                }

                location.reload();
            }
        });
    }
}

$("#submitFormCreate").click(function () {
    const form = $('form#formCreateUser').serialize();
    const userId = $("[name='id']").val();
    const urlType = statusForm === "create" ? "post" : 'put';
    const url = statusForm === 'create' ? '/action/user/create' : `/action/user/update/${userId}`;

    $.ajax({
        url: baseUrl + url,
        type: urlType,
        dataType: "json",
        data: form,
        success: function (data) {
            if (data.valid) {
                alert(data.message);
            } else {
                alert(data.message);
            }

            location.reload();
        },

        error: function (xhr, status, errorThrown) {
            if (xhr.status === 422) {
                console.log(xhr)
                let alertHtml = '<div class="alert alert-danger"><ul>';
                const message = xhr.responseJSON.message;
                for (const key in message) {
                    if (Object.hasOwnProperty.call(message, key)) {
                        const element = message[key];

                        alertHtml += `<li>${element[0]}</li>`
                    }
                }
                alertHtml += '</ul></div>';
                $("#alertMessage").html(alertHtml);
            }

        }
    });
});

