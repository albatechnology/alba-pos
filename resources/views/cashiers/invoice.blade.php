<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order Invoice</title>
</head>

<body>
    <h1>{{ $order->id }}</h1>
    <h1>{{ $order->invoice_number }}</h1>
    <h1>{{ $order->total_price }}</h1>
    <h1>{{ $order->created_at }}</h1>
    <script>
        window.print();
        window.onafterprint = window.close;
    </script>
</body>

</html>
