<!DOCTYPE html>
<html>
<head>
    <title>Email</title>
</head>
<body>
<h1>{{ $data['title'] ?? 'Welcome' }}</h1>

<p>Hello {{ $data['message']['user'] ?? 'User' }}</p>
<p>Your account has been created successfully, and here is your first Voucher: <b>{{ $data['message']['voucher'] ?? 'N/A' }}</b></p>
</body>
</html>
