<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>BOOKING HASIL</title>
</head>

<body>
    <p>Nama: {{ $user_name }}</p>
    <p>Nomor Transaksi: {{ $no_transaksi }}</p>
    <p>Waktu: {{ $start_time }}</p>
    <p>Category Jenis: {{ $menu_item }}</p>
    <p>Total Harga: {{ number_format($total_price, '2', ',') }}</p>
    <p>Status: {{ $status }}</p>
    <div style="width: 150px; height: 150px">
        {{ $qrcode }}
    </div>
</body>

</html>
