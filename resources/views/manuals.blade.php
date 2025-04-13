<x-app>
@include('Components.navbar')
@section('title', 'Manuals')
    <link href="{{ asset('admin_assets/vendor/font-awesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <body class="bg-gradient-light">
        <div class="container">
            <div class="card o-hidden border-0 shadow-lg">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="mb-0">Manuals</h3>
                    </div>
                    <hr />
                </div>
            </div>
        </div>
        <script src="{{ asset('admin_assets/js/bootstrap.bundle.min.js') }}"></script>
    </body>
</x-app>
