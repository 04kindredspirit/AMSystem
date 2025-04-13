<x-app>
@include('Components.navbar')
@section('title', 'Parent Directory')
<link href="{{ asset('admin_assets/vendor/font-awesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
<body class="bg-gradient-light text-dark">
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="mb-0">Edit Parent {{ $parent->parentFirst_name }}'s Information</h3>
                </div>
                <hr class="border-secondary">
                <form method="POST" action="{{ route('ParentDirectory.update', $parent->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="card border border-top-0 border-danger">
                        <div class="card-header bg-danger text-white text-center">
                            Parent/Guardian Information
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning" role="alert">
                                If information is unavailable, please type "N/A" in the field.
                            </div>
                            <div class="form-group">
                                <label for="linkToStudent">Link to enrolled student/s</label>
                                <select class="form-control" name="parentRelationship_to_student" id="linkToStudent">
                                    <option value="" disabled selected>- Select Student -</option>
                                    <option value="Mother">Mother</option>
                                    <option value="Father">Father</option>
                                    <option value="Guardian">Guardian</option>
                                    <option value="Relative">Relative</option>
                                    <option value="N/A">Not In The List</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="pFirstn">First Name</label>
                                        <input type="text" class="form-control form-control-user rounded" name="parentFirst_name" id="pFirstn" value="{{ $parent->parentFirst_name }}">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="pMiddlen">Middle Name</label>
                                        <input type="text" class="form-control form-control-user rounded" name="parentMiddle_name" id="pMiddlen" value="{{ $parent->parentMiddle_name }}">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="pLastn">Last Name</label>
                                        <input type="text" class="form-control form-control-user rounded" name="parentLast_name" id="pLastn" value="{{ $parent->parentLast_name }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="pBirthd">Birthdate</label>
                                        <input type="date" class="form-control form-control-user rounded" name="parentBirthdate" id="pBirthd" value="{{ $parent->parentBirthdate }}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label for="pHighestEd">Highest Educational Attainment</label>
                                    <select class="form-control" name="pHighestEd" id="pHighestEd">
                                        <option selected>- Select Highest Educational Attainment -</option>
                                        <option value="College">College/University</option>
                                        <option value="Postgraduate">Graduate/Postgraduate</option>
                                        <option value="HighSchool">High School Graduate</option>
                                        <option value="Elementary">Elementary Graduate</option>
                                        <option value="N/A">N/A</option>
                                    </select>
                                </div>
                                <div class="col-5">
                                    <div class="form-group">
                                        <label for="pOccupation">Occupation</label>
                                        <input type="text" class="form-control form-control-user rounded" name="parentOccupation" id="pOccupation" value="{{ $parent->parentOccupation }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="pMobilen">Mobile Number</label>
                                        <input type="number" class="form-control form-control-user rounded" name="parentMobile_number" id="pMobilen" value="{{ $parent->parentMobile_number }}">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="pEmail">Email</label>
                                        <input type="email" class="form-control form-control-user rounded" name="parentEmail" id="pEmail" value="{{ $parent->parentEmail }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="pAddress">Address</label>
                                <input type="text" class="form-control form-control-user rounded" name="parentAddress" id="pAddress" value="{{ $parent->parentAddress }}">
                            </div>
                            <hr />
                            <div class="footer d-flex justify-content-center">
                                <a href="{{ route('ParentDirectory') }}" class="btn btn-danger mr-2">Back</a>
                                <button class="btn btn-primary">Save</button>
                            </div> 
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('admin_assets/js/bootstrap.bundle.min.js') }}"></script>

</body>
</x-app>