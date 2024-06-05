<!-- resources/views/login.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .btn-primary {
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .form-check-label {
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>{{__('Login')}}</h2>
        <form id="loginForm" method="POST" action="{{ route('loginPost') }}">
            @csrf

            <div class="form-group">
                <label for="email">{{__('Email')}}</label>
                <input id="email" type="email" class="form-control" name="email" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">{{__('Password')}}</label>
                <input id="password" type="password" class="form-control" name="password" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    {{__('Login')}}
                </button>
            </div>
        </form>
    </div>

    <script>
        // JavaScript for handling form submission via AJAX
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission
            
            // Fetch API request
            fetch('{{ route('login', ['locale' => app()->getLocale()]) }}', {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}' // Include CSRF token in the request header
                }
            })
            
            .then(response => response.json())
            .then(data => {
                // Handle response data accordingly
                console.log(data);
                if (data.success) {
                    // Redirect to another page upon successful login
                    // window.location.href = '/welcome';

                    <button onclick="window.location.href='{{ route('home', ['locale' => app()->getLocale()]) }}'">Welcome</button>

                } else if (data.failed) {
                    // Display error message or handle failed login
                    alert(data.failed.response.msg);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    </script>
</body>
</html>
