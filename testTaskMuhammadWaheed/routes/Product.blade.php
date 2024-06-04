<!-- resources/views/users.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Products</h1>

    <div>
        <button onclick="location.href='{{ route('product.create') }}'">Create</button>
    </div>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>image</th>
                <th>Category</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($product as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <button onclick="location.href='{{ route('product.edit', $product->id) }}'">Edit</button>
                    <form method="POST" action="{{ route('product.destroy', $product->id) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
