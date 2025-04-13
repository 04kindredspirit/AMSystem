<x-app>
@include('Components.navbar')
@section('title', 'School Year')
<link href="{{ asset('admin_assets/vendor/font-awesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">

<meta name="csrf-token" content="{{ csrf_token() }}">
<body class="bg-gradient-light text-dark">
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg mb-4">
        <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="mb-0">School Year</h3>
                </div>
                <hr class="border-secondary">
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
                <div class="d-flex justify-content-end mb-3">
                    <div class="p-2">
                        @if (auth()->user()->role != 'Teacher')
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#sy-modal">+ Add New School Year</button>
                        @endif
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table id="myTable" class="table table-striped table-hover" style="width:100%">
                        <thead class="table-primary text-center">
                            <tr>
                                <th width="60%">School Year</th>
                                <th width="20%">Status</th>
                                @if(auth()->user()->role != 'Teacher' && auth()->user()->role !='Accountant')
                                <th width="20%">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @if ($schoolyear->count() > 0)
                                @foreach ($schoolyear as $sy)
                                    <tr>
                                        <td>{{ $sy->school_year ?? '' }}</td>
                                        <td class="text-center">
                                            <span id="status-badge-{{ $sy->id }}" class="badge {{ $sy->status == 'Active' ? 'bg-success' : 'bg-danger' }} text-white">{{ $sy->status }}</span>
                                        </td>
                                        @if(auth()->user()->role != 'Teacher' && auth()->user()->role !='Accountant')
                                        <td class="text-center">
                                            <button type="button" class="btn btn-warning edit-btn my-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $sy->id }}" data-id="{{ $sy->id }}" data-name="{{ $sy->school_year }}" data-level="{{ $sy->status }}" style="font-size: 10px;">
                                                Edit
                                            </button>
                                            <div class="modal fade" id="editModal{{ $sy->id }}" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
                                                <div class="modal-dialog modal-xl">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="POST" action="{{ route('SystemControls.updateSY', $sy->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="card border border-top-0 border-danger">
                                                                    <div class="card-header bg-danger text-white text-center">
                                                                        Edit School Year
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="form-group row">
                                                                            <div class="col-12 col-sm-6 col-md-4">
                                                                                <label for="schoolYear">School Year</label>
                                                                                <input type="text" class="form-control form-control-user rounded" name="schoolYear" id="schoolYear{{ $sy->id }}" value="{{ $sy->school_year }}" required>
                                                                            </div>
                                                                            <div class="col-12 col-sm-6 col-md-8">
                                                                                <label for="syStatus">Section Name</label>
                                                                                <select class="form-control" name="syStatus" id="syStatus{{ $sy->status }}" required>
                                                                                    <option value="" disabled selected>- Select Status -</option>
                                                                                    <option value="Active">Active</option>
                                                                                    <option value="Inactive">Inactive</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer d-flex justify-content-center">
                                                                            <button type="button" data-bs-dismiss="modal" class="btn btn-danger">Close</button>
                                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                                        </div> 
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Button trigger for remove modal -->
                                            <button type="button" class="btn btn-danger my-1" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $sy->id }}" style="font-size: 10px;">
                                                Remove
                                            </button>
                                            <!-- Remove Modal -->
                                            <div class="modal fade" id="deleteModal{{ $sy->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title" id="deleteModalLabel">Remove School Year?</h5>
                                                            <button type="button" class="btn btn-link" data-bs-dismiss="modal" aria-label="Close">
                                                                <i class="fas fa-times text-white"></i>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <strong>Are you sure you want to remove this School Year? Please note that this action cannot be undone.</strong>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <form action="{{ route('SystemControls.deleteSY', $sy->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Remove</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="modal fade" id="sy-modal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('SystemControls.createSY') }}">
                                    @csrf
                                    <div class="card border border-top-0 border-danger">
                                        <div class="card-header bg-danger text-white text-center">
                                            Add New School Year
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group row">
                                                <div class="col-12 col-sm-6 col-md-4">
                                                    <label for="schoolYear">School Year</label>
                                                    <input type="text" class="form-control form-control-user rounded" name="schoolYear" id="schoolYear" required>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-8">
                                                    <label for="syStatus">Section Name</label>
                                                    <select class="form-control" name="syStatus" id="syStatus" required>
                                                        <option value="" disabled selected>- Select Status -</option>
                                                        <option value="Active">Active</option>
                                                        <option value="Inactive">Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer d-flex justify-content-center">
                                                <button type="button" data-bs-dismiss="modal" class="btn btn-danger">Close</button>
                                                <button class="btn btn-primary">Save</button>
                                            </div> 
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

    <script src="{{ asset('admin_assets/js/bootstrap.bundle.min.js') }}"></script>

</body>
</x-app>