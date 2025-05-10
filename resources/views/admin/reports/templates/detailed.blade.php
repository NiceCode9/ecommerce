<!DOCTYPE html>
<html>

<head>
    <title>Laporan Detail Penjualan</title>
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

        .summary {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
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

        .product-list {
            margin: 0;
            padding-left: 15px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Laporan Detail Penjualan</h1>
        <p>Sparepart Komputer</p>
        <div class="period">
            Periode: {{ $start_date }} - {{ $end_date }}
        </div>
    </div>

    <div class="summary">
        Total Pendapatan: <strong>Rp {{ number_format($total_revenue, 0, ',', '.') }}</strong>
    </div>

    <table>
        <thead>
            <tr>
                <th>No. Pesanan</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Status</th>
                <th>Total</th>
                <th>Produk</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->nomor_pesanan }}</td>
                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                    <td>{{ $order->pengguna->name ?? 'Guest' }}</td>
                    <td>{{ $order->status_label }}</td>
                    <td>Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</td>
                    <td>
                        <ul class="product-list">
                            @foreach ($order->detailPesanan as $item)
                                <li>{{ $item->produk->nama }} ({{ $item->jumlah }} x Rp
                                    {{ number_format($item->harga, 0, ',', '.') }})</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dibuat pada: {{ now()->format('d F Y H:i') }}</p>
    </div>
</body>

</html>
