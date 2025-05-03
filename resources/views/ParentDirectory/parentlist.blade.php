<x-app>
@include('Components.navbar')
@section('title', 'Parent Directory')
<link href="{{ asset('admin_assets/vendor/font-awesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="{{ asset('dataTable/css/dataTables.bootstrap5.css') }}">

<script defer src="{{ asset('dataTable/js/jquery-3.7.1.js') }}"></script>
<script defer src="{{ asset('dataTable/js/bootstrap.bundle.min.js') }}"></script>
<script defer src="{{ asset('dataTable/js/dataTables.js') }}"></script>
<script defer src="{{ asset('dataTable/js/dataTables.bootstrap5.js') }}"></script>
<script defer src="{{ asset('dataTable/js/script.js') }}"></script>

<body class="bg-gradient-light text-dark">
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="mb-0">Parent/Guardian Directory</h3>
                    @if (auth()->user()->role != 'Teacher')
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#reg-modal">+ Add New Parent</button>
                    @endif
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
                <div class="table-responsive">
                    <table id="myTable" class="table table-striped text-center" style="width:100%">
                        <thead class="table-primary">
                            <tr>
                                <th class="text-center">Full Name</th>
                                <th class="text-center">Mobile</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Address</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($parents->count() > 0)
                                @foreach($parents as $prnts)
                                    <tr>
                                        <td><a href="{{ route('ParentDirectory.show', $prnts->id) }}">{{ $prnts->parentFirst_name ?? '' }} {{ $prnts->parentMiddle_name ?? '' }} {{ $prnts->parentLast_name ?? '' }}</a></td>
                                        <td>{{ $prnts->parentMobile_number ?? '' }}</td>
                                        <td>{{ $prnts->parentEmail ?? '' }}</td>
                                        <td>{{ $prnts->parentAddress ?? '' }}</td>
                                        <td class="text-center d-flex justify-content-between">
                                            <a href="{{ route('ParentDirectory.show', $prnts->id) }}" type="button" class="btn btn-secondary" style="font-size:7px;"><i class="fas fa-eye"></i> View</a>
                                            @if(auth()->user()->role != 'Teacher' && auth()->user()->role !='Accountant')
                                            <a href="{{ route('ParentDirectory.edit', $prnts->id) }}" type="button" class="btn btn-warning mx-1" style="font-size:7px;"><i class="fas fa-edit"></i> Edit</a>
                                            @endif
                                            <!-- button trigger for remove modal -->
                                            @if(auth()->user()->role != 'Teacher' && auth()->user()->role !='Accountant' && auth()->user()->role !='SuperAdmin')
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $prnts->id }}" style="font-size: 10px;">
                                            Remove
                                            </button>
                                            <!-- remove modal -->
                                            <div class="modal fade" id="deleteModal{{ $prnts->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title" id="exampleModalLabel">Remove Parent/Guardian Information?</h5>
                                                            <button type="button" class="btn btn-link" data-bs-dismiss="modal" aria-label="Close">
                                                                <i class="fas fa-times text-white"></i>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <strong>Are you sure you want to remove the Parent/Guardian information? Please note that this action cannot be undone.</strong>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <form action="{{ route('parents.destroy', $prnts->id) }}" method="POST">
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
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- parent form -->
                <div class="modal fade" id="reg-modal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modal-title">Add New Parent</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('ParentDirectory.create') }}">
                                    @csrf
                                    <div class="card border border-top-0 border-danger">
                                        <div class="card-header bg-danger text-white text-center">
                                            Parent/Guardian Information
                                        </div>
                                        <div class="card-body">
                                            <div class="alert alert-warning" role="alert">
                                                If information is unavailable, please type "N/A" in the field.
                                                <div>
                                                    <small>Please ensure that all fields marked with a red asterisk <span class="text-danger">(*)</span> are completed, as they are required.</small>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-12 col-sm-6 col-md-8">
                                                        <label>Link to enrolled student/s <span class="text-danger">*</span></label>
                                                        <select class="form-control" name="student_id[]" required>
                                                            <option value="" disabled selected>- Select Student -</option>
                                                            
                                                            <!-- display grouped students -->
                                                            @foreach ($groupedStudentsMap as $group_id => $group)
                                                                <option value="group_{{ $group_id }}">
                                                                    [
                                                                    {{ implode('], [', array_map(function($student) { 
                                                                        return $student->studentFirst_name . ' ' . $student->studentLast_name; 
                                                                    }, $group->toArray())) }}
                                                                    ]
                                                                </option>
                                                            @endforeach

                                                            <!-- display individual students (excluding those already in groups) -->
                                                            @foreach($students as $student)
                                                                @php
                                                                    $isGrouped = false;
                                                                    foreach ($groupedStudentsMap as $group) {
                                                                        if ($group->contains('id', $student->id)) {
                                                                            $isGrouped = true;
                                                                            break;
                                                                        }
                                                                    }
                                                                @endphp

                                                                @if (!$isGrouped)
                                                                    <option value="student_{{ $student->id }}">
                                                                        {{ $student->studentFirst_name . ' ' . $student->studentLast_name }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-12 col-sm-6 col-md-4">
                                                        <label for="linkToStudent">Relationship to Student <span class="text-danger">*</span></label>
                                                        <select class="form-control" name="linkToStudent" id="linkToStudent" required>
                                                            <option value="" disabled selected>- Select Relationship -</option>
                                                            <option value="Mother">Mother</option>
                                                            <option value="Father">Father</option>
                                                            <option value="Guardian">Guardian</option>
                                                            <option value="Relative">Relative</option>
                                                            <option value="N/A">Not In The List</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-12 col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <label for="pFirstn">First Name <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control form-control-user rounded" name="pFirstn" id="pFirstn" required>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <label for="pMiddlen">Middle Name</label>
                                                        <input type="text" class="form-control form-control-user rounded" name="pMiddlen" id="pMiddlen">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <label for="pLastn">Last Name <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control form-control-user rounded" name="pLastn" id="pLastn" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-12 col-sm-6 col-md-3">
                                                    <div class="form-group">
                                                        <label for="pBirthd">Birthdate <span class="text-danger">*</span></label>
                                                        <input type="date" class="form-control form-control-user rounded" name="pBirthd" id="pBirthd" required>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-4">
                                                    <label for="pHighestEd">Highest Educational Attainment <span class="text-danger">*</span></label>
                                                    <select class="form-control" name="pHighestEd" id="pHighestEd" required>
                                                        <option selected>- Select Highest Educational Attainment -</option>
                                                        <option value="College">College/University</option>
                                                        <option value="Postgraduate">Graduate/Postgraduate</option>
                                                        <option value="HighSchool">High School Graduate</option>
                                                        <option value="Elementary">Elementary Graduate</option>
                                                        <option value="N/A">N/A</option>
                                                    </select>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-5">
                                                    <div class="form-group">
                                                        <label for="pOccupation">Occupation <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control form-control-user rounded" name="pOccupation" id="pOccupation">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-12 col-sm-6 col-md-6">
                                                    <div class="form-group">
                                                        <label for="pMobilen">Mobile Number <span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control form-control-user rounded" name="pMobilen" id="pMobilen" required>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-6">
                                                    <div class="form-group">
                                                        <label for="pEmail">Email <span class="text-danger">*</span></label>
                                                        <input type="email" class="form-control form-control-user rounded" name="pEmail" id="pEmail" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-12 col-sm-6 col-md">
                                                    <label for="pAddress">Address <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control form-control-user rounded" name="pAddress" id="pAddress" required>
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