let statusForm = "";

$(document).ready(function () {
    $("#table-dbs").DataTable();
    $("#table-treg").DataTable();
});

function newRealisasi() {
    $("#titleModal").text("Create Realisasi Sustain");
    $("#formRealisasi")[0].reset();
    statusForm = "create";
    $("#alertMessage").empty();
}

function updateSustain(userId, salesId) {
    statusForm = "update";
    $("#alertMessage").empty();
    $.getJSON(
        baseUrl + `/r-sustain/user/${userId}/sales/${salesId}`,
        (data) => {
            let html = "";
            $("#listTitle").text("Update Realisasi Sustain");

            if (data.valid) {
                data.data.forEach((v, i) => {
                    html += `<tr>
                <th scope="row">${i + 1}</th>
                <td>${v.month}</td>
                <td>${v.value}</td>
                <td><button type="button" class="btn btn-sm btn-sm-square btn-danger" onclick="deleteSustain(${
                    v.id
                })"><i class="fa fa-trash"></i><button type="button" class="btn btn-sm btn-sm-square btn-info" onclick="detail(${
                        v.id
                    })")"><i class="fa fa-pencil-alt"></i></button></button>
              </tr>`;
                });
            }

            $("#body-list").html(html);
        }
    );
}

function detail(id) {
    var link = document.createElement("a");
    link.href = baseUrl + `/view/realisasi-sustain-detail/${id}?page=sustain`;
    link.target = "_blank";
    link.click();
}

function deleteSustain(idUser) {
    if (confirm("hapus?")) {
        $.ajax({
            url: baseUrl + `/action/realisasi/sustain/delete/${idUser}`,
            type: "delete",
            dataType: "json",
            data: {
                _token: tokenCsrf,
            },
            success: function (data) {
                if (data.valid) {
                    alert(data.message);
                } else {
                    alert(data.message);
                }

                location.reload();
            },
        });
    }
}

$("#submitFormCreate").click(function () {
    const form = $("form#formRealisasi").serialize();
    const userId = $("[name='id']").val();
    const urlType = statusForm === "create" ? "post" : "put";
    const url =
        statusForm === "create"
            ? "/action/realisasi/sustain/create"
            : `/action/realisasi/sustain/update/${userId}`;

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

            if (statusForm === "create") {
                location.reload();
            } else {
                location.replace(baseUrl + '/view/realisasi-sustain');
            }
        },
        error: function (xhr, status, errorThrown) {
            if (xhr.status === 422) {
                console.log(xhr);
                let alertHtml = '<div class="alert alert-danger"><ul>';
                const message = xhr.responseJSON.message;
                for (const key in message) {
                    if (Object.hasOwnProperty.call(message, key)) {
                        const element = message[key];

                        alertHtml += `<li>${element[0]}</li>`;
                    }
                }
                alertHtml += "</ul></div>";
                $("#alertMessage").html(alertHtml);
            }
        },
    });
});
