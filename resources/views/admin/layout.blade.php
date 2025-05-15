<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title') | Brmja Tech</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css" />
    <link rel="stylesheet" href="{{ asset('admin/css/main.css') }}">
    
    @stack('styles')
</head>

<body>
    <div class="page">
        @include('admin.partials.sidebar')
        
        <div class="content">
            @include('admin.partials.header')
            
            <div class="wrapper mt-0">
                <div class="container">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('admin/js/app.js') }}"></script>
    
    @stack('scripts')
</body>
</html>