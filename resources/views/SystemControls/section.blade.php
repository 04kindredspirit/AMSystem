<x-app>
@include('Components.navbar')
@section('title', 'Section')
<link href="{{ asset('admin_assets/vendor/font-awesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">

<meta name="csrf-token" content="{{ csrf_token() }}">
<body class="bg-gradient-light text-dark">
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="mb-0">List of Sections</h3>
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
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#section-modal">+ Add New Section</button>
                        @endif
                    </div>
                </div>
                
                <div class="table-responsive table-sm">
                    <table id="myTable" class="table table-striped table-hover" style="width:100%">
                        <thead class="table-primary text-center">
                            <tr>
                                <th width="40%">Section</th>
                                <th width="20%">Level</th>
                                <th width="20%">Status</th>
                                @if(auth()->user()->role != 'Teacher' && auth()->user()->role !='Accountant')
                                <th width="20%">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @if ($sections->count() > 0)
                                @foreach ($sections as $section)
                                    <tr>
                                        <td>{{ $section->section_name ?? '' }}</td>
                                        <td>{{ $section->section_level ?? '' }}</td>
                                        <td></td>
                                        @if(auth()->user()->role != 'Teacher' && auth()->user()->role !='Accountant')
                                        <td class="text-center">
                                            <button type="button" class="btn btn-warning edit-btn my-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $section->id }}" data-id="{{ $section->id }}" data-name="{{ $section->section_name }}" data-level="{{ $section->section_level }}" style="font-size: 10px;">
                                                Edit
                                            </button>
                                            <div class="modal fade" id="editModal{{ $section->id }}" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
                                                <div class="modal-dialog modal-xl">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="POST" action="{{ route('SystemControls.updateSection', $section->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="card border border-top-0 border-danger">
                                                                    <div class="card-header bg-danger text-white text-center">
                                                                        Edit Section
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="form-group row">
                                                                            <div class="col-12 col-sm-6 col-md-4">
                                                                                <label for="sectionLevel">Level</label>
                                                                                <select class="form-control" name="sectionLevel" id="sectionLevel{{ $section->id }}" required>
                                                                                    <option value="" disabled selected>- Select Level -</option>
                                                                                    <option value="Pre Kinder">Pre Kinder</option>
                                                                                    <option value="Kinder">Kinder</option>
                                                                                    <option value="Level 1-3">1-3</option>
                                                                                    <option value="Level 4-6">4-6</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-12 col-sm-6 col-md-8">
                                                                                <label for="sectionName">Section Name</label>
                                                                                <input type="text" class="form-control form-control-user rounded" name="sectionName" id="sectionName{{ $section->id }}" value="{{ $section->section_name }}" required>
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
                                            @if(auth()->user()->role != 'Teacher' && auth()->user()->role !='Accountant' && auth()->user()->role !='SuperAdmin')
                                            <button type="button" class="btn btn-danger my-1" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $section->id }}" style="font-size: 10px;">
                                                Remove
                                            </button>
                                            <!-- Remove Modal -->
                                            <div class="modal fade" id="deleteModal{{ $section->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title" id="deleteModalLabel">Remove Section?</h5>
                                                            <button type="button" class="btn btn-link" data-bs-dismiss="modal" aria-label="Close">
                                                                <i class="fas fa-times text-white"></i>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <strong>Are you sure you want to remove this section? Please note that this action cannot be undone.</strong>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <form action="{{ route('SystemControls.deleteSection', $section->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Remove</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="modal fade" id="section-modal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('SystemControls.createSection') }}">
                                    @csrf
                                    <div class="card border border-top-0 border-danger">
                                        <div class="card-header bg-danger text-white text-center">
                                            Add New Section
                                        </div>
                                        <div class="card-body">
                                            <div class="form group row mb-3">
                                                <div class="col-12 col-sm-6 col-md-4">
                                                    <label for="sectionLevel">Level</label>
                                                    <select class="form-control" name="sectionLevel" id="sectionLevel" required>
                                                        <option value="" disabled selected>- Select Level -</option>
                                                        <option value="Pre Kinder">Pre Kinder</option>
                                                        <option value="Kinder">Kinder</option>
                                                        <option value="Level 1-3">1-3</option>
                                                        <option value="Level 4-6">4-6</option>
                                                    </select>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-8">
                                                    <label for="sectionName">Section Name</label>
                                                    <input type="text" class="form-control form-control-user rounded" name="sectionName" id="sectionName" required>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.edit-btn');

            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const sectionId = button.getAttribute('data-id');
                    const sectionName = button.getAttribute('data-name');
                    const sectionLevel = button.getAttribute('data-level');

                    document.getElementById(`sectionName${sectionId}`).value = sectionName;
                    document.getElementById(`sectionLevel${sectionId}`).value = sectionLevel;
                });
            });
        });
    </script>
    
</body>
</x-app>