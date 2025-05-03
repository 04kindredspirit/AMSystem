<x-app>
@include('Components.navbar')
@section('title', 'Parent Directory')
<link href="{{ asset('admin_assets/vendor/font-awesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
<body class="bg-gradient-light text-dark">
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="mb-0">Edit User {{ $user->first_name }}'s Information</h3>
                    <a href="{{ route('SystemControls.access-security') }}" class="close" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <hr class="border-secondary">
                <form method="POST" action="{{ route('SystemControls.update', $user->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="card border border-top-0 border-danger">
                        <div class="card-header bg-danger text-white text-center">
                            User Information
                        </div>
                        <div class="card-body">
                            <!-- Role Dropdown -->
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <div class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                            Select Role
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="selectRoleDropdown">
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="updateRole('SuperAdmin')">SuperAdmin</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="updateRole('Accountant')">Accountant</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="updateRole('Teacher')">Teacher</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="text" class="form-control form-control-user rounded" id="userRole" name="role" value="{{ $user->role }}" readonly required>
                                </div>
                            </div>

                            <!-- Name Fields -->
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="text" class="form-control form-control-user rounded" id="exampleFirstName" name="first_name" value="{{ $user->first_name }}" placeholder="First Name" required>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control form-control-user rounded" id="exampleLastName" name="last_name" value="{{ $user->last_name }}" placeholder="Last Name" required>
                                </div>
                            </div>

                            <!-- Email and Phone Fields -->
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="email" class="form-control form-control-user rounded" id="exampleInputEmail" name="email" value="{{ $user->email }}" placeholder="Email Address" required>
                                </div>
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="text" class="form-control form-control-user rounded" id="exampleInputNumber" name="phone_number" value="{{ $user->phone_number }}" placeholder="Phone Number" required>
                                </div>
                            </div>

                            <!-- Password Fields -->
                            <!-- <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="password" class="form-control form-control-user rounded" id="userPassword" name="password" placeholder="Password">
                                </div>
                            </div> -->

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary btn-user btn-block rounded">
                                Update Account
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('admin_assets/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        // Function to update the role in the input field
        function updateRole(role) {
            document.getElementById("userRole").value = role;
        }

        // Pre-fill the role dropdown with the current role
        document.addEventListener('DOMContentLoaded', function() {
            const currentRole = "{{ $user->role }}";
            document.getElementById("userRole").value = currentRole;
        });
    </script>
</body>
</x-app>