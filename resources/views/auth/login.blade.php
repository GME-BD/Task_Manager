<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Task Manager</title>
    <link rel="shortcut icon" href="{{ asset('assets/img/logo-circle.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        /* Define a modern color palette */
        :root {
            --primary-color: #007bff;
            /* Standard blue, can be swapped for a vibrant color like #5b86e5 */
            --primary-dark: #0056b3;
            --background-start: #e0f7fa;
            /* Light cyan for gradient start */
            --background-end: #f5f5f5;
            /* Off-white for gradient end */
            --card-border-radius: 1.5rem;
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            /* Using a subtle, modern gradient background */
            background: linear-gradient(135deg, var(--background-start), var(--background-end));
            font-family: 'Poppins', sans-serif;
        }

        /* Card Styling for a softer, elevated look */
        .card {
            border: none !important;
            /* Remove Bootstrap's default card border */
            border-radius: var(--card-border-radius);
            box-shadow: var(--card-shadow);
            overflow: hidden;
            /* Ensures header/footer rounded corners look right */
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            /* Subtle lift on hover */
        }

        /* Header Styling */
        .card-header {
            /* Use the primary color for a vibrant header */
            background-color: var(--primary-color);
            background: linear-gradient(90deg, #5b86e5 0%, #36d1dc 100%);
            /* Blue/Cyan gradient */
            color: white;
            padding: 2.5rem 1rem;
            /* More vertical padding */
            font-size: 1.5rem;
            font-weight: 600;
            text-align: center;
            border-top-left-radius: var(--card-border-radius);
            border-top-right-radius: var(--card-border-radius);
            border-bottom: none;
            /* Remove default border */
        }

        /* Logo styling for white text/background header */
        .card-header img {
            max-height: 40px;
            /* Control logo size */
            filter: invert(100%) brightness(200%);
            /* Makes the image white if it's black */
        }

        /* Form Controls */
        .form-control {
            border-radius: 0.75rem;
            /* More rounded inputs */
            padding: 0.75rem 1rem;
            border: 1px solid #ced4da;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
            /* Primary color shadow on focus */
        }

        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        /* Primary Button */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            font-weight: 600;
            /* Bolder text for CTA */
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
            transition: all 0.2s ease-in-out;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
            /* Button lift effect */
        }

        /* Footer */
        .card-footer {
            background-color: white;
            border-top: none;
            border-bottom-left-radius: var(--card-border-radius);
            border-bottom-right-radius: var(--card-border-radius);
            padding: 1.5rem;
        }

        .card-footer p {
            margin-bottom: 0;
            font-size: 0.9rem;
            color: #6c757d;
        }

        .card-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <img src="{{ asset('assets/img/logo-horizontal.png') }}" class="img-fluid mb-2"
                            alt="task manager logo">
                        <div>Task Manager Login</div>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-4">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" name="email" id="email" class="form-control"
                                    placeholder="admin@example.com" required autofocus>
                                @error('email')
                                    <span class="text-danger mt-1 d-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                                @error('password')
                                    <span class="text-danger mt-1 d-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-4 form-check">
                                <input type="checkbox" name="remember" id="remember" class="form-check-input">
                                <label for="remember" class="form-check-label text-secondary">Remember Me</label>
                            </div>
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary">Sign In</button>
                            </div>
                        </form>
                    </div>

                    <div class="card-footer text-center">
                        <p>Developed by: <a href="#">Admin & IT Department</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>