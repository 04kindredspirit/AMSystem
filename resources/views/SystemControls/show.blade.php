<x-app>
@include('Components.navbar')
@section('title', 'Parent Directory')
<link href="{{ asset('admin_assets/vendor/font-awesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
<body class="bg-gradient-light text-dark">
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="mb-0">System User Profile</h3>
                </div>
                <hr class="border-secondary">
                <div class="card mb-3" style="max-width: 100%;">
                    <div class="row g-0">
                        <!-- Left column: Image and Upload Button -->
                        <div class="col-md-3 bg-secondary bg-danger rounded-left d-flex flex-column align-items-center justify-content-center py-3">
                        <img src="{{ asset($user->image ?? 'admin_assets/img/undraw_profile.svg') }}" id="parentImage" class="img-fluid mb-2 p-3" style="width:200px; height:200px; object-fit:cover; border-radius: 50%; " alt="Profile Image">
                        </div>
                        <!-- Right column: Personal Information -->
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">Personal Information</h5>
                                <p class="card-text h5 text-danger font-weight-bold">
                                    {{ $user->first_name }} {{ $user->last_name }}
                                </p>
                                <span class="card-text badge badge-warning mb-3 text-uppercase p-1 text-dark">
                                    {{ $user->role }}
                                </span>
                                <p class="card-text"><i class="fas fa-envelope"></i> <strong>Email Address:</strong> {{ $user->email }}</p>
                                <p class="card-text"><i class="fas fa-mobile"></i> <strong>Mobile Number:</strong> {{ $user->phone_number }}</p>
                                <p class="card-text"><i class="fas fa-user-check"></i> <strong>Status:</strong> {{ $user->status }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('admin_assets/js/bootstrap.bundle.min.js') }}"></script>

</body>
</x-app>