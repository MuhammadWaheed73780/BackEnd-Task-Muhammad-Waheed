<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
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
        
        #productsTable {
            width: 100%;
            border-collapse: collapse;
        }
        
        #productsTable th, #productsTable td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        #productsTable th {
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
    <h1>{{ __('Welcome') }}</h1>

    <!-- Add, Update, Delete Buttons -->
    <div id="addUpdateDeleteButtons">
        <button class="action-button" onclick="window.location.href='{{ route('add-product', ['locale' => app()->getLocale()]) }}'">{{__('Add Product')}}</button>
        <button class="action-button" onclick="window.location.href='{{ route('update-product', ['locale' => app()->getLocale()]) }}'">{{__('Update Product')}}</button>
        <button class="action-button" onclick="window.location.href='{{ route('delete-product', ['locale' => app()->getLocale()]) }}'">{{__('Delete Product')}}</button>
        <button class="action-button" onclick="window.location.href='{{ route('filter-product', ['locale' => app()->getLocale()]) }}'">{{__('Filter Product')}}</button>
    </div>
    
    <!-- <button class="action-button" onclick="window.location.href='{{ route('manage-category', ['locale' => 'en']) }}'">{{__('Category Page')}}</button> -->

    <button class="action-button" onclick="window.location.href='{{ route('manage-category', ['locale' => app()->getLocale()]) }}'">
        {{__('Category Page')}}
    </button>

    <table id="productsTable">
        <thead>
            <tr>
            <th>{{__('ID')}}</th>
            <th>{{__('Name')}}</th>
            <th>{{__('Description')}}</th>
            <th>{{__('Category')}}</th>
            <th>{{__('Image')}}</th> </tr>
        </thead>
        <tbody>
            @if (count($products) > 0)  
                @for ($i = 0; $i < count($products); $i++)
                    <tr>
                        <td>{{ $products[$i]->id }}</td>
                        <td>{{ $products[$i]->Name }}</td>
                        <td>{{ $products[$i]->Description }}</td>
                        <td>{{ $products[$i]->categoryName }}</td>
                        <td>
                            <img src="{{ asset('productPictures/' . $products[$i]->Image) }}" alt="Product Image" style="max-width: 100px;">
                        </td>
                    </tr>
                @endfor
            @else
            <tr>
                <td colspan="5">{{__('No products found for your search')}}</td> </tr>
            @endif
        </tbody>
    </table>

    <!-- Display error message if any -->
    @if (session('error'))
        <div class="error-message">{{ session('error') }}</div>
    @endif
</body>
</html>
