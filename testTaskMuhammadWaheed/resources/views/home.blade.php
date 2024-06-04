<!-- resources/views/home.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    <h1>Welcome to the Home Page</h1>
    
    <ul>
        <li><a href="{{ route('Category') }}">Category Page</a></li>    
        <li><a href="{{ route('Product') }}">Product Page</a></li>
    </ul>
</body>
</html>
