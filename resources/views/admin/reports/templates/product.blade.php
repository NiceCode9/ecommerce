<!DOCTYPE html>
<html>

<head>
    <title>Laporan Penjualan Produk</title>
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

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Laporan Penjualan Produk</h1>
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
                <th>No</th>
                <th>Produk</th>
                <th>Kategori</th>
                <th>Terjual</th>
                <th class="text-right">Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $index => $product)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $product->nama }}</td>
                    <td>{{ $product->kategori->nama ?? '-' }}</td>
                    <td>{{ $product->total_terjual }}</td>
                    <td class="text-right">Rp {{ number_format($product->total_pendapatan, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dibuat pada: {{ now()->format('d F Y H:i') }}</p>
    </div>
</body>

</html>
