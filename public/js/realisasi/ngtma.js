let statusForm = "";

$(document).ready(function () {
    $("#table-dbs").DataTable();
    $("#table-treg").DataTable();
});

function newRealisasi() {
    $("#titleModal").text("Create Realisasi Ngtma");
    $("#formRealisasi")[0].reset();
    statusForm = "create";
    $("#alertMessage").empty();
}

function updateNgtma(userId, salesId) {
    statusForm = "update";
    $("#alertMessage").empty();
    $.getJSON(baseUrl + `/r-ngtma/user/${userId}/sales/${salesId}`, (data) => {
        let html = "";
        $("#listTitle").text("Update Realisasi Ngtma");

        if (data.valid) {
            data.data.forEach((v, i) => {
                html += `<tr>
                <th scope="row">${i + 1}</th>
                <td>${v.month}</td>
                <td>${v.value}</td>
                <td><button type="button" class="btn btn-sm btn-sm-square btn-danger" onclick="deleteData(${
                    v.id
                })"><i class="fa fa-trash"></i><button type="button" class="btn btn-sm btn-sm-square btn-info" onclick="detail(${v.id})")"><i class="fa fa-pencil-alt"></i></button></button>
              </tr>`;
            });
        }

        $("#body-list").html(html);
    });
}

function detail(id) {
    var link = document.createElement("a");
    link.href = baseUrl + `/view/realisasi-ngtma-detail/${id}?page=ngtma`;
    link.target = "_blank";
    link.click();
}

function deleteData(idUser) {
    if (confirm("hapus?")) {
        $.ajax({
            url: baseUrl + `/action/realisasi/ngtma/delete/${idUser}`,
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
            ? "/action/realisasi/ngtma/create"
            : `/action/realisasi/ngtma/update/${userId}`;

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
                location.replace(baseUrl + '/view/realisasi-ngtma');
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
