<x-app>
@include('Components.navbar')
@section('title', 'Access Security')
    <link href="{{ asset('admin_assets/vendor/font-awesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('admin_assets/bootstrap-5.3.3-dist/css/switch.css') }}">
    
<meta name="csrf-token" content="{{ csrf_token() }}">
<body class="bg-gradient-light">
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="mb-0">User Management</h3>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#reg-modal">+ Add New Account</button>
                </div>
                <hr class="border-secondary">
                <!-- Success and Error Messages -->
                @if (session('success'))
                    <div class="alert alert-success" id="alert-sucess">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="closeAlert('success-alert')">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger" id="error-alert" style="display: flex; justify-content: space-between; align-items: center;">
                        <ul class="mt-3 text-sm" style="flex-grow: 1;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="closeAlert('success-alert')" style="margin-left: auto;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-striped table table-hover" style="width:100%">
                        <thead class="table-primary text-center">
                            <tr>
                                <th></th>
                                <th>Role</th>
                                <th>Name</th>
                                <th>Email Address</th>
                                <th>Phone Number</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($user->count() > 0)
                                @foreach($user as $usr)
                                <tr>
                                    <td>
                                        <form id="status-form-{{ $usr->id }}" action="{{ route('user.toggle-status', $usr->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="form-check form-switch">
                                                <input class="form-check-input status-switch" type="checkbox" role="switch" id="status-switch-{{ $usr->id }}" 
                                                    {{ $usr->status == 'Active' ? 'checked' : '' }} data-user-id="{{ $usr->id }}">
                                            </div>
                                        </form>
                                    </td>
                                    <td>{{ $usr->role ?? '' }}</td>
                                    <td>{{ $usr->first_name ?? '' }} {{ $usr->last_name ?? '' }}</td>
                                    <td>{{ $usr->email ?? '' }}</td>
                                    <td>{{ $usr->phone_number ?? '' }}</td>
                                    <td class="text-center">
                                        <span id="status-badge-{{ $usr->id }}" class="badge {{ $usr->status == 'Active' ? 'bg-success' : 'bg-danger' }} text-white">{{ $usr->status }}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('SystemControls.show', $usr->id) }}" type="button" class="btn btn-secondary" style="font-size: 10px;">View</a>
                                        @if(auth()->user()->role != 'Teacher' && auth()->user()->role !='Accountant')
                                            <a href="{{ route('SystemControls.edit', $usr->id) }}" type="button" class="btn btn-warning my-1" style="font-size: 10px;">Edit</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="6" class="text-center">No users found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                
                <!--modal iteself-->
                <div class="modal fade" id="reg-modal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modal-title">Add New Account</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('register.save') }}" class="user">
                                    @csrf
                                    <div class="card border border-top-0 border-danger">
                                        <div class="card-header bg-danger text-white text-center">
                                            Account Registration
                                        </div>
                                        <div class="card-body">
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
                                                    <input type="text" class="form-control form-control-user rounded" id="exampleRole" name="role" placeholder="Role" readonly required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6 mb-3 mb-sm-0">
                                                    <input type="text" class="form-control form-control-user rounded" id="exampleFirstName" name="first_name" placeholder="First Name" required>
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control form-control-user rounded" id="exampleLastName" name="last_name" placeholder="Last Name" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6 mb-3 mb-sm-0">
                                                    <input type="email" class="form-control form-control-user rounded" id="exampleInputEmail" name="email" placeholder="Email Address" required>
                                                </div>
                                                <div class="col-sm-6 mb-3 mb-sm-0">
                                                    <input type="text" class="form-control form-control-user rounded" id="exampleInputNumber" name="phone_number" placeholder="Phone Number" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6 mb-3 mb-sm-0">
                                                    <input type="password" class="form-control form-control-user rounded" id="exampleInputPassword" name="password" placeholder="Password" required>
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="password" class="form-control form-control-user rounded" id="exampleRepeatPassword" name="password_confirmation" placeholder="Repeat Password">
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-user btn-block rounded">
                                                Register Account
                                            </button>
                                        </div>
                                    </div>
                                </form>                     
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
    
    <script src="{{ asset('admin_assets/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        function updateRole(role) {
            document.getElementById("exampleRole").value = role;
        }

        function closeAlert(alertId) {
            var alert = document.getElementById(alertId);
            alert.classList.add('fade-out');
            setTimeout(function() {
                alert.style.display = 'none';
            }, 500);
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.status-switch').forEach(function(switchElement) {
                switchElement.addEventListener('change', function() {
                    const userId = this.getAttribute('data-user-id');
                    const form = document.getElementById(`status-form-${userId}`);
                    const badge = document.getElementById(`status-badge-${userId}`);

                    fetch(form.action, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ status: this.checked ? 'Active' : 'Inactive' })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            badge.textContent = this.checked ? 'Active' : 'Inactive';
                            badge.className = `badge ${this.checked ? 'bg-success' : 'bg-danger'} text-white`;
                        } else {
                            this.checked = !this.checked;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        this.checked = !this.checked;
                    });
                });
            });
        });
    </script>
    
</x-app>