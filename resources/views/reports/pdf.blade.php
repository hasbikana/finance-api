<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Laporan Keuangan' }}</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 12px; }
        h2 { color: #0F766E; margin-bottom: 5px; }
        h4 { color: #14B8A6; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #0F766E; color: white; padding: 8px 6px; text-align: left; font-size: 11px; }
        td { padding: 6px; border-bottom: 1px solid #e5e7eb; }
        tr:nth-child(even) { background-color: #f9fafb; }
        .summary { margin-top: 20px; padding: 12px; background: #f0fdf4; border-left: 4px solid #22C55E; }
        .summary h4 { color: #22C55E; margin-bottom: 8px; }
        .summary p { margin: 4px 0; }
        .income { color: #22C55E; font-weight: bold; }
        .expense { color: #ef4444; font-weight: bold; }
        .footer { margin-top: 20px; text-align: right; font-size: 10px; color: #9ca3af; }
    </style>
</head>
<body>
    <h2>FinanceApp - {{ $title ?? 'Laporan Keuangan' }}</h2>
    <h4>Generated: {{ $generated_at ?? date('d F Y H:i') }}</h4>

    <table>
        <thead>
            <tr><th>Tanggal</th><th>Tipe</th><th>Kategori</th><th>Jumlah</th><th>Deskripsi</th></tr>
        </thead>
        <tbody>
            @foreach($transactions ?? [] as $t)
            <tr>
                <td>{{ $t->date instanceof \Carbon\Carbon ? $t->date->format('d M Y') : $t->date }}</td>
                <td class="{{ $t->type === 'income' ? 'income' : 'expense' }}">{{ $t->type === 'income' ? 'Income' : 'Expense' }}</td>
                <td>{{ $t->category?->name ?? '-' }}</td>
                <td class="{{ $t->type === 'income' ? 'income' : 'expense' }}">Rp {{ number_format($t->amount, 0, ',', '.') }}</td>
                <td>{{ $t->description ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <h4>Ringkasan</h4>
        <p class="income">Total Income: Rp {{ number_format($summary['total_income'] ?? 0, 0, ',', '.') }}</p>
        <p class="expense">Total Expense: Rp {{ number_format($summary['total_expense'] ?? 0, 0, ',', '.') }}</p>
        <p><strong>Balance: Rp {{ number_format($summary['balance'] ?? 0, 0, ',', '.') }}</strong></p>
        <p>Total Transaksi: {{ $summary['transaction_count'] ?? 0 }}</p>
    </div>

    <div class="footer">FinanceApp - Generated at {{ $generated_at ?? date('d F Y H:i') }}</div>
</body>
</html>
