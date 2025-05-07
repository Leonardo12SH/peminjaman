$(document).ready(function() {
    __fetchCard();
    __fetchItems();
    __grafikWeek();
    __grafikMonth();
    __onChange();
})

var start_year = new Date().getFullYear();
var nowMonth = new Date().getMonth();

for (var i = start_year; i >= 2019; i--) {
    $("#year").append(
        `<option value="${i}" ${i == start_year ? "selected" : ""}>${i}</option> `
    );
    $("#year_2").append(
        `<option value="${i}" ${i == start_year ? "selected" : ""}>${i}</option> `
    );
}

let date = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10',
            '11', '12', '13', '14', '15', '16', '17', '18', '19', '20',
            '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31'
        ];

let data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
    0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
    0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0
];

const __fetchCard = () => {
    $.ajax({
        url: `/admin/card`,
        type: "GET",
        dataType: "JSON",
        beforeSend: function () {},
        success: function (res) {
            if (res.code == 200) {
                $('#total').html(res.total)
                $('#failed').html(res.failed)
                $('#sukses').html(res.success)
                $('#omset').html(`Rp. ${res.omset[0].total}`)
            }
        },
        error: function (err) {},
        complete: function () {},
    });
}

const __fetchItems = () => {
    $.ajax({
        url: `/admin/MenuItem`,
        type: "GET",
        dataType: "JSON",
        beforeSend: function () {},
        success: function (res) {
            if (res.code == 200) {
                var htm = ``;
                if(res.data.length>0){
                    $.each(res.data, function(k, v){
                        htm += `
                            <tr>
                                <td>${k+1}</td>
                                <td>${v.name}</td>
                                <td>Rp. ${v.price}</td>
                            </tr>
                        `
                    })
                    $('#table_item tbody').html(htm)
                }
            }
        },
        error: function (err) {},
        complete: function () {},
    });
}

const __grafikWeek = () => {
    var year = $("#year_2").val().length < 1 ? start_year : $("#year_2").val();
    var month =  $("#month").val().length < 1 ? nowMonth + 1 : $("#month").val();
    $.ajax({
        url: `/admin/week`,
        type: "GET",
        dataType: "JSON",
        data: {
            month,
            year
        },
        beforeSend: function () {},
        success: function (res) {
            if (res.code == 200) {
                console.log(res);
                grafikPerTanggal.data.datasets[0].data = [
                    res.data[1],
                    res.data[2],
                    res.data[3],
                    res.data[4],
                    res.data[5],
                    res.data[6],
                    res.data[7],
                    res.data[8],
                    res.data[9],
                    res.data[10],
                    res.data[11],
                    res.data[12],
                    res.data[13],
                    res.data[14],
                    res.data[15],
                    res.data[16],
                    res.data[17],
                    res.data[18],
                    res.data[19],
                    res.data[20],
                    res.data[21],
                    res.data[22],
                    res.data[23],
                    res.data[24],
                    res.data[25],
                    res.data[26],
                    res.data[27],
                    res.data[28],
                    res.data[29],
                    res.data[30],
                    res.data[31],
                ],
                grafikPerTanggal.update()
            }
        },
        error: function (err) {},
        complete: function () {},
    });
}

const __grafikMonth = () => {
    var year =
    $("#year").val().length < 1 ? start_year : $("#year").val();

    $.ajax({
        url: `/admin/month`,
        type: "GET",
        dataType: "JSON",
        data: {
            year
        },
        beforeSend: function () {},
        success: function (res) {
            if (res.code == 200) {
                // console.log(res);
                if(res.data.length>0){
                    res.data.forEach(v => {
                        myChart.data.datasets[0].data[v.monthKey - 1] = v.total
                    })
                }else{
                    myChart.data.datasets[0].data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                }
                myChart.update()
                // console.log(myChart.data.datasets[0].data);
            }
        },
        error: function (err) {},
        complete: function () {},
    });
}

const __onChange= () => {
    $("#year").on("change", function() {
        __grafikMonth();
    });

    $("#year_2").on("change", function() {
        __grafikWeek();
    });

    $("#month").on("change", function() {
        __grafikWeek();
    });
}

//month
var ctx = document.getElementById('month-chart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    responsive: false,
    data: {
        labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September',
            'Oktober', 'November', 'Desember'
        ],
        datasets: [{
            label: 'Grafik Penumpang',
            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            backgroundColor: [
                'rgba(78, 115, 223, 0.05)'
            ],
            borderColor: [
                'rgba(78, 115, 223, 1)'
            ],
        }]
    },
    options: {
        responsive: true,
        scales: {
            yAxes: [{
                ticks: {
                    min: 0,
                }
            }]
        }
    }
});

//by date
var perTanggal = document.getElementById('day-chart').getContext('2d');
var grafikPerTanggal = new Chart(perTanggal, {
    type: 'line',
    responsive: false,
    data: {
        labels: date,
        datasets: [{
            label: 'Grafik per Tanggal',
            data: data,
            backgroundColor: [
                'rgba(78, 115, 223, 0.05)'
            ],
            borderColor: [
                'rgba(78, 115, 223, 1)'
            ],
        }]
    },
    options: {
        responsive: true,
        scales: {
            yAxes: [{
                ticks: {
                    min: 0,
                }
            }]
        }
    }
});