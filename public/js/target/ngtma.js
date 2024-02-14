let statusForm = "";

const months = [
    "januari",
    "febuari",
    "maret",
    "april",
    "mei",
    "juni",
    "juli",
    "agustus",
    "september",
    "oktober",
    "november",
    "desember",
];

$(document).ready(function () {
    $("#table-dbs").DataTable();
    $("#table-treg").DataTable();
    $("#month").select2({
        width: "100%",
        dropdownParent: $(".modal"),
    });
    $("#users").select2({
        width: "100%",
        dropdownParent: $(".modal"),
    });
});

function sampleTarget()
{
    window.location = baseUrl + '/example/sample_target.xlsx';
}

function newTargetUpload() {
    $('#uploadtarget').attr('action', baseUrl + '/action/import-target/ngtma');
}

function newTarget() {
    $("#titleModal").text("Create Target Ngtma");
    $("#formTarget")[0].reset();
    statusForm = "create";
    $("#alertMessage").empty();
}

function updateUserBtn(idUser) {
    statusForm = "update";
    $("#alertMessage").empty();
    $.getJSON(baseUrl + "/target/ngtma/" + idUser, (data) => {
        $("#titleModal").text("Update Target Ngtma");
        $("[name='id']").val(data.data.id);

        $("[name='user_id']").val(data.data.user_id);
        $("[name='value_januari']").val(data.data.value_januari);
        $("[name='value_febuari']").val(data.data.value_febuari);
        $("[name='value_maret']").val(data.data.value_maret);
        $("[name='value_april']").val(data.data.value_april);
        $("[name='value_mei']").val(data.data.value_mei);
        $("[name='value_juni']").val(data.data.value_juni);
        $("[name='value_juli']").val(data.data.value_juli);
        $("[name='value_agustus']").val(data.data.value_agustus);
        $("[name='value_september']").val(data.data.value_september);
        $("[name='value_oktober']").val(data.data.value_oktober);
        $("[name='value_november']").val(data.data.value_november);
        $("[name='value_desember']").val(data.data.value_desember);
        $("[name='value_year']").val(data.data.value_year);
    });
}

function deleteSustain(idUser) {
    if (confirm("hapus?")) {
        $.ajax({
            url: baseUrl + `/action/target/ngtma/delete/${idUser}`,
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
    const form = $("form#formTarget").serialize();
    const userId = $("[name='id']").val();
    const urlType = statusForm === "create" ? "post" : "put";
    const url =
        statusForm === "create"
            ? "/action/target/ngtma/create"
            : `/action/target/ngtma/update/${userId}`;

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
