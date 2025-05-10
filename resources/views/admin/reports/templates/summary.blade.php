<!DOCTYPE html>
<html>

<head>
    <title>Laporan Ringkasan Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
        }

        .period {
            font-weight: bold;
            margin-bottom: 15px;
        }

        .summary-box {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .summary-item {
            border: 1px solid #ddd;
            padding: 15px;
            width: 32%;
            text-align: center;
        }

        .summary-item h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
        }

        .summary-item p {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        .chart-container {
            margin: 20px 0;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Laporan Ringkasan Penjualan</h1>
        <p>Sparepart Komputer</p>
        <div class="period">
            Periode: {{ $start_date }} - {{ $end_date }}
        </div>
    </div>

    <div class="summary-box">
        <div class="summary-item">
            <h3>Total Pendapatan</h3>
            <p>Rp {{ number_format($total_revenue, 0, ',', '.') }}</p>
        </div>
        <div class="summary-item">
            <h3>Total Transaksi</h3>
            <p>{{ number_format($total_orders, 0, ',', '.') }}</p>
        </div>
        <div class="summary-item">
            <h3>Rata-rata per Transaksi</h3>
            <p>Rp {{ number_format($average_order_value, 0, ',', '.') }}</p>
        </div>
    </div>

    <h3>Status Pesanan</h3>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders_by_status as $status => $count)
                <tr>
                    <td>{{ $status }}</td>
                    <td>{{ $count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3 style="margin-top: 30px;">Penjualan Harian</h3>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Total Penjualan</th>
            </tr>
        </thead>
        <tbody>
            @foreach (array_combine($daily_sales['dates'], $daily_sales['sales']) as $date => $sales)
                <tr>
                    <td>{{ $date }}</td>
                    <td>Rp {{ number_format($sales, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dibuat pada: {{ now()->format('d F Y H:i') }}</p>
    </div>
</body>

</html>
