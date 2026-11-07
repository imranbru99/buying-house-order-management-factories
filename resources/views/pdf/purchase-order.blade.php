<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>PO {{ $order->number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { font-size: 18px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
    </style>
</head>
<body>
    <h1>Purchase Order {{ $order->number }}</h1>
    <p><strong>Buyer:</strong> {{ $order->buyer?->name }}</p>
    <p><strong>Status:</strong> {{ $order->status }}</p>
    <p><strong>Total:</strong> {{ $order->total_amount }} {{ $order->currency }}</p>

    <table>
        <thead>
            <tr>
                <th>Line</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Line Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->line_no }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->unit_price }}</td>
                    <td>{{ $item->line_total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
