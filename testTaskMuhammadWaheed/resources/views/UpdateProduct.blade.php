<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f9f9f9;
        }

        h1 {
            color: #333;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            resize: vertical;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>{{__('Update Product')}}</h1>

    <!-- Form to add a new product -->
    <form action="{{ route('updateProduct') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="id">{{__('Product ID')}}:</label><br>
        <input type="text" id="id" name="id" placeholder="{{__('Enter product id')}}"><br>

        <label for="name">{{__('Product Name')}}:</label><br>
        <input type="text" id="name" name="name" placeholder="{{__('Enter product name')}}"><br>

        <label for="description">{{__('Description')}}:</label><br>
        <textarea id="description" name="description" placeholder="{{__('Enter product description')}}"></textarea><br>

        <label for="category">{{__('Category ID')}}:</label><br>
        <input type="number" id="category" name="category" placeholder="{{__('Enter Category ID')}}"><br>

        <label for="image">{{__('Product Image')}}:</label><br>
        <input type="file" id="image" name="image"><br>

        <button type="submit">{{__('Update Product')}}</button>
    </form>

    <!-- Display error message if any -->
    @if (session('error'))
        <div class="error-message">{{ session('error') }}</div>
    @endif
</body>
</html>
