$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    __fetchJam();
    __onChange();
    __clickJam();
    __submit();
    __btnAccept();
    __btnReject();
    var defaultItemId = 0;
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();

    if (dd < 10) {
        dd = "0" + dd;
    }
    if (mm < 10) {
        mm = "0" + mm;
    }

    today = yyyy + "-" + mm + "-" + dd;
    const inputDate = document.getElementById("date");
    inputDate.setAttribute("min", today);
    inputDate.value = today;
    __loadItem(defaultItemId, today);
});

var jam = [];
var arrJam = [];
for (let startHourInDay = 10; startHourInDay < 22; startHourInDay++) {
    let date = new Date(null);
    date.setHours(startHourInDay);
    let timeString = date.toLocaleTimeString("id-ID", {
        hour: "2-digit",
        minute: "2-digit",
    });
    jam.push(timeString);
}

function __fetchJam() {
    var htm = ``;
    $.each(jam, function (k, v) {
        htm = `
        <button id="btn" class="btn btn-sm btn-info mt-1 btn-jam" dt=${v}>${v}</button>
        `;
        $("#jam").append(htm);
    });
}

function __loadItem(item_id, today) {
    $.ajax({
        url: `/customer/item`,
        type: "GET",
        dataType: "JSON",
        data: { item_id, today },
        beforeSend: function () {},
        success: function (res) {
            if (res.code == 200) {
                if (res.data.length > 0) {
                    var htm = `<option disabled selected>-- Choose Category --</option>`;
                    $.each(res.data, function (k, v) {
                        htm += `
                            <option value="${v.id}" dt="${v.name}">${v.name} | ${v.price}</option>
                        `;
                    });

                    $('[name="item_id"]').html(htm);
                }
            }
        },
        error: function (err) {},
        complete: function () {},
    });
}

function __onChange() {
    var opt = {};
    $(document).on("change", "[name]", function () {
        $("#option")
            .find("[name]")
            .each(function (k, v) {
                opt[$(v).attr("name")] = $(v).val();
            });
        $("#tgl").html(opt.tanggal);
        __getItem(opt);
    });
}

function __getItem({ item_id, tanggal }) {
    var dt = { item_id, today: tanggal };
    $.ajax({
        url: `/customer/item`,
        type: "GET",
        dataType: "JSON",
        data: dt,
        beforeSend: function () {},
        success: function (res) {
            if (res.code == 200) {
                __loadTransaksi(res.booked);
                $("span#item").html(res.data[0].name);
                $("span#harga").html(`${res.data[0].price}`);
            }
        },
        error: function (err) {},
        complete: function () {},
    });
}

function __loadTransaksi(data) {
    let success = [];
    $(document)
        .find(`.btn-jam`)
        .removeClass("btn-success")
        .addClass("btn-info")
        .attr("disabled", false);
    arrJam = [];
    jam = [];

    $("#total").html(0);
    $("#total_jam").html(`0 Jam`);
    $("#detail_jam").html("");
    $.each(data, function (index, booking) {
        const existJam = JSON.parse(booking.transaksi.jam);
        success.push(...existJam);
    });
    if (success.length > 0) {
        $.each(success, function (k, v) {
            $(document)
                .find(`.btn-jam[dt="${v}"]`)
                .removeClass("btn-info")
                .addClass("btn-success")
                .attr("disabled", true);
        });
    }
}

function __clickJam() {
    $(document).on("click", "#btn", "#jam", function (e) {
        e.preventDefault();
        $(this).attr("disabled", true);
        arrJam.push($(this).attr("dt"));
        var length = arrJam.length;
        __jam(length);
    });
}

function __jam(data) {
    if (arrJam.length > 0) {
        var htm = ``;
        $.each(arrJam, function (k, v) {
            htm += `${v}, `;
        });
        $("#detail_jam").html(htm);
    }
    var jumlah = data;
    var harga = $(document).find('span[id="harga"]').text();
    $("#total").html(harga * jumlah);
    $("#total_jam").html(`${jumlah} Jam`);
}

function __submit() {
    var data = {};
    $(document).on("click", "#btn-submit", function () {
        var menu = $(document).find('select[name="item_id"]').val();
        var tanggal = $(document).find('input[name="tanggal"]').val();
        var user_id = $(document).find('input[name="user_id"]').val();
        var note = $("#note").val();
        var harga = $(document).find('span[id="harga"]').html();
        var total = $(document).find('span[id="total"]').html();
        var jam = arrJam;

        data.menu_id = menu;
        data.jam = JSON.stringify(jam);
        data.user_id = user_id;
        data.note = note;
        data.price = Number(harga);
        data.total_price = Number(total);
        $.ajax({
            url: "/customer/transaksi",
            type: "POST",
            dataType: "JSON",
            data,
            success: function (res) {
                window.location.reload();
            },
            error: function (error) {
                //
            },
        });
    });
}

function __btnAccept() {
    $(document).on("click", "#btn-accept", function () {
        const id = $(this).attr("data-id");
        const menu_id = $(this).attr("menu-id");
        $.ajax({
            type: "POST",
            url: "/admin/transaksi/booking",
            data: { id, menu_id },
            dataType: "JSON",
            success: function (res) {
                window.location.reload();
            },
            error: function (err) {
                //
            },
        });
    });
}
function __btnReject() {
    $(document).on("click", "#btn-reject", function () {
        const id = $(this).attr("data-id");
        $.ajax({
            type: "POST",
            url: "/admin/transaksi/booking/reject",
            data: { id },
            dataType: "JSON",
            success: function (res) {
                window.location.reload();
            },
            error: function (err) {
                //
            },
        });
    });
}
