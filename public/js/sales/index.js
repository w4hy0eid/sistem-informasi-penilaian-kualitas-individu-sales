function newSales() {
    $("#titleModal").text("Create Sales");
    $('#formSales')[0].reset();
    statusForm = 'create';
    $("#alertMessage").empty();
}

function updateSalesBtn(idUser) {
    statusForm = "update";
    $("#alertMessage").empty();
    $.getJSON(baseUrl + "/sales/" + idUser, (data) => {
        $("#titleModal").text("Update Sales");
        $("[name='id']").val(data.data.id);

        $("[name='judul_project']").val(data.data.judul_project);
        $("[name='nama_pelanggan']").val(data.data.nama_pelanggan);
        $("[name='lama_kontrak']").val(data.data.lama_kontrak);
        $("[name='mitra']").val(data.data.mitra);
        $("[name='deal_dibulan']").val(data.data.deal_dibulan);
        $("[name='nilai_project']").val(data.data.nilai_project);
        $("[name='pembayaran_bulanan']").val(data.data.pembayaran_bulanan);
        $("[name='type']").val(data.data.type);
    });
}

function deleteSalesBtn(idUser) {
    if (confirm("hapus?")) {
        $.ajax({
            url: baseUrl + `/action/sales/delete/${idUser}`,
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

$(document).ready(function () {
    $("#table-sales").DataTable();
});

$("#submitFormCreate").click(function () {
    const form = $("form#formSales").serialize();
    const userId = $("[name='id']").val();
    const urlType = statusForm === "create" ? "post" : "put";
    const url =
        statusForm === "create"
            ? "/action/sales/create"
            : `/action/sales/update/${userId}`;

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
