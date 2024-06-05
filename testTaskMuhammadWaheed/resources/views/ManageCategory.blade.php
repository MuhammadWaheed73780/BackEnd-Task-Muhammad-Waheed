<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        
        h1 {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }
        
        #categoryTable {
            width: 100%;
            border-collapse: collapse;
        }
        
        #categoryTable th, #categoryTable td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        #categoryTable th {
            background-color: #f2f2f2;
        }
        
        #addUpdateDeleteButtons {
            text-align: center;
            margin-bottom: 20px;
        }

        .action-button {
            margin: 0 10px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .action-button:hover {
            background-color: #0056b3;
        }
        
        .error-message {
            color: #dc3545;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>{{__('Welcome Category')}}</h1>

    <!-- Add, Update, Delete Buttons -->
    <div id="addUpdateDeleteButtons">
        <button class="action-button" onclick="window.location.href='{{ route('add-category', ['locale' => app()->getLocale()]) }}'">{{__('Add Category')}}</button>
        <button class="action-button" onclick="window.location.href='{{ route('update-category', ['locale' => app()->getLocale()]) }}'">{{__('Update Category')}}</button>
        <button class="action-button" onclick="window.location.href='{{ route('delete-category', ['locale' => app()->getLocale()]) }}'">{{__('Delete Category')}}</button>

    </div>

    <button class="action-button" onclick="window.location.href='{{ route('manage-products', ['locale' => app()->getLocale()]) }}'">{{__('Product Page')}}</button>
    
    
    <table id="categoryTable">
        <thead>
            <tr>
            <th>{{__('ID')}}</th>
            <th>{{__('Name')}}</th>
        </thead>
        <tbody>
            @if (count($cats) > 0)  
                @for ($i = 0; $i < count($cats); $i++)
                    <tr>
                        <td>{{ $cats[$i]->id }}</td>
                        <td>{{ $cats[$i]->Name }}</td>
                    </tr>
                @endfor
            @else
            <tr>
                <td colspan="5">{{__('No categories found for your search')}}.</td> </tr>
            @endif
        </tbody>
    </table>

    <!-- Display error message if any -->
    @if (session('error'))
        <div class="error-message">{{ session('error') }}</div>
    @endif
</body>
</html>
