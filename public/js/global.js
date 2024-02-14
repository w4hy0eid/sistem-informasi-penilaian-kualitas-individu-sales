$.getJSON(baseUrl + "/users", (data) => {
    let html = "";

    if (data.data?.length > 0) {
        data.data.forEach((v) => {
            html += ` <option value="${v.id}">${v.name} - ${v.level}</option>`;
        });
    }

    $("#users").append(html);
});

var triggerTabList = [].slice.call(document.querySelectorAll("#myTab a"));
triggerTabList.forEach(function (triggerEl) {
    var tabTrigger = new bootstrap.Tab(triggerEl);

    triggerEl.addEventListener("click", function (event) {
        event.preventDefault();
        tabTrigger.show();
    });
});

$(document).ready(function () {
    const inputTarget = [
        "value_januari",
        "value_febuari",
        "value_maret",
        "value_april",
        "value_mei",
        "value_juni",
        "value_juli",
        "value_agustus",
        "value_september",
        "value_oktober",
        "value_november",
        "value_desember",
    ];

    inputTarget.forEach((v) => {
        $(`[name='${v}']`).change(function () {
            let otherVal = 0;
            const val = $(this).val();

            const filterVal = inputTarget.filter((x) => x !== v);
            for (const iterator of filterVal) {
                const valOther = $(`[name='${iterator}']`).val();

                otherVal += parseInt(valOther);
            }
            const total = otherVal + parseInt(val);
            $("[name='value_year']").val(total);
        });
    });
});

function clickCircleBottom() {
    let a = document.getElementsByClassName('content_bottom_right');

    if(a[0].style.display == "none") {
        a[0].style.display = "block";
    } else {
        a[0].style.display = "none";
    }
}
