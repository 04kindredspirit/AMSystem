<x-app>
@include('Components.navbar')
@section('title', 'Add Student')
<link href="{{ asset('admin_assets/vendor/font-awesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">

<meta name="csrf-token" content="{{ csrf_token() }}">
<body class="bg-gradient-light text-dark">
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="mb-0">Add New Student</h3>
                </div>
                <hr class="border-secondary">
                <div class="alert alert-warning d-flex flex-row" role="alert1">
                    <div class="d-flex align-items-center mr-2">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                    </div>
                    <div>
                        <strong>IMPORTANT NOTICE</strong>
                        <div class="text">
                        Please check for existing siblings in the <a href="{{ route('ManageStudent/StudentDirectory') }}" class="text-primary">Student Directory</a> before adding a new student. 
                        Use the <a href="{{ route('ManageStudent/StudentDirectory') }}" class="text-primary">Add Student</a> form if a sibling is already enrolled.
                        </div>
                    </div>
                </div>
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
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active bg-primary text-white" id="nav-parent-tab" data-bs-toggle="tab" 
                        data-bs-target="#nav-parent" type="button" role="tab" aria-control="nav-parent"
                        aria-selected="true">Parent / Guardian Information</button>

                        <button class="nav-link bg-danger text-white" id="nav-student-tab" data-bs-toggle="tab" 
                        data-bs-target="#nav-student" type="button" role="tab" aria-control="nav-picture"
                        aria-selected="false">Student Information</button>

						<button class="nav-link bg-warning text-white" id="nav-tuition-tab" data-bs-toggle="tab" 
                        data-bs-target="#nav-tuition" type="button" role="tab" aria-control="nav-password"
                        aria-selected="false">Tuition Information</button>
                    </div>
                </nav>
                <form action="{{ route('ManageStudent/addstudent') }}" method="POST">
                    @csrf
                    <!-- Parent/Guardian Information Tab -->
                    <div class="tab-content border border-bottom-0 border-danger" id="nav-tabContent">
                        <div class="tab-pane fade show active p-3" id="nav-parent" role="tabpanel"
                            aria-labelledby="nav-parent-tab">
                            <h3>Parent/Guardian Information</h3>
                            <p>If information is unavailable, please type "N/A" in the field.</p>
                            <div class="form-group">
                                <label for="relationshipToStudent">Relationship to Student <span class="text-danger">*</span></label>
                                <select class="form-control" name="parent_relationship_to_student" id="relationshipToStudent" required>
                                    <option value="" disabled {{ old('parent_relationship_to_student') == null ? 'selected' : '' }}>- Select Relationship -</option>
                                    <option value="Mother" {{ old('parent_relationship_to_student') == 'Mother' ? 'selected' : '' }}>Mother</option>
                                    <option value="Father" {{ old('parent_relationship_to_student') == 'Father' ? 'selected' : '' }}>Father</option>
                                    <option value="Guardian" {{ old('parent_relationship_to_student') == 'Guardian' ? 'selected' : '' }}>Guardian</option>
                                    <option value="Relative" {{ old('parent_relationship_to_student') == 'Relative' ? 'selected' : '' }}>Relative</option>
                                    <option value="N/A" {{ old('parent_relationship_to_student') == 'N/A' ? 'selected' : '' }}>Not In The List</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-user rounded" name="parent_first_name" value="{{ old('parent_first_name') }}" placeholder="First Name" required>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>Middle Name</label>
                                        <input type="text" class="form-control form-control-user rounded" name="parent_middle_name" value="{{ old('parent_middle_name') }}" placeholder="Middle Name">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-user rounded" name="parent_last_name" value="{{ old('parent_last_name') }}" placeholder="Last Name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>Birthdate <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control form-control-user rounded" name="parent_birthdate" value="{{ old('parent_birthdate') }}" placeholder="Birthdate" required>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4">
                                    <label for="parentEducational">Highest Educational Attainment <span class="text-danger">*</span></label>
                                    <select class="form-control" name="parent_educational_attainment" id="parentEducational" required>
                                        <option value="" disabled {{ old('parent_educational_attainment') == null ? 'selected' : '' }}>- Select Highest Educational Attainment -</option>
                                        <option value="College" {{ old('parent_educational_attainment') == 'College' ? 'selected' : '' }}>College/University</option>
                                        <option value="Postgraduate" {{ old('parent_educational_attainment') == 'Postgraduate' ? 'selected' : '' }}>Graduate/Postgraduate</option>
                                        <option value="HighSchool" {{ old('parent_educational_attainment') == 'HighSchool' ? 'selected' : '' }}>High School Graduate</option>
                                        <option value="Elementary" {{ old('parent_educational_attainment') == 'Elementary' ? 'selected' : '' }}>Elementary Graduate</option>
                                        <option value="N/A" {{ old('parent_educational_attainment') == 'N/A' ? 'selected' : '' }}>N/A</option>
                                    </select>
                                </div>
                                <div class="col-12 col-sm-6 col-md-5">
                                    <div class="form-group">
                                        <label>Occupation <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-user rounded" name="parent_occupation" value="{{ old('parent_occupation') }}" placeholder="Occupation" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label>Mobile Number <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control form-control-user rounded" name="parent_mobile_number" maxlength="11" value="{{ old('parent_mobile_number') }}" placeholder="Mobile Number" required>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label>Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control form-control-user rounded" name="parent_email" value="{{ old('parent_email') }}" placeholder="Email" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-user rounded" name="parent_address" value="{{ old('parent_address') }}" placeholder="Address" required>
                            </div>
                        </div>
                    </div>
                    <!-- Student Information Tab -->
                    <div class="tab-content border border-top-0 border-bottom-0 border-danger" id="nav-tabContent">
                        <div class="tab-pane fade p-3" id="nav-student" role="tabpanel"
                            aria-labelledby="nav-student-tab">
                            <h3>Student Information</h3>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <label>LRN <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-user rounded" name="student_LRN" placeholder="LRN" value="{{ old('student_LRN') }}" required>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <label>Section <span class="text-danger">*</span></label>
                                        <select class="form-control" name="student_section" required>
                                            <option value="" disabled {{ old('student_section') == null ? 'selected' : '' }}>- Select Section -</option>
                                            @foreach($sectionData['data'] as $section)
                                                <option value="{{ $section->section_name }}" {{ old('student_section') == $section->section_name ? 'selected' : '' }}>
                                                    {{ $section->section_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <label>School Year <span class="text-danger">*</span></label>
                                        <select class="form-control" name="school_year" required>
                                            <option value="" disabled {{ old('school_year') == null ? 'selected' : '' }}>- Select School Year -</option>
                                            @foreach($schoolyear as $sy)
                                                <!-- Debug the school year -->
                                                <option value="{{ $sy->school_year }}" {{ old('school_year') == $sy->school_year ? 'selected' : '' }}>
                                                    {{ $sy->school_year }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>Name Ext. (Sr., Jr.)</label>
                                        <input type="text" class="form-control form-control-user rounded" name="student_name_ext" value="{{ old('student_name_ext') }}" placeholder="Name Ext. (Sr., Jr.)">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-8">
                                    <div class="form-group">
                                        <label>First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-user rounded" name="student_first_name" value="{{ old('student_first_name') }}" placeholder="First Name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label>Middle Name</label>
                                        <input type="text" class="form-control form-control-user rounded" name="student_middle_name" value="{{ old('student_middle_name') }}" placeholder="Middle Name">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label>Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-user rounded" name="student_last_name" value="{{ old('student_last_name') }}" placeholder="Last Name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>Gender <span class="text-danger">*</span></label>
                                        <select class="form-control" name="student_gender" value="{{ old('student_gender') }}" required>
                                            <option value="" disabled {{ old('student_gender') == null ? 'selected' : '' }}>- Select Gender -</option>
                                            <option value="Male" {{ old('student_gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ old('student_gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>Birthdate <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control form-control-user rounded" name="student_birthdate" value="{{ old('student_birthdate') }}" placeholder="Birthdate" required>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>Birthorder</label>
                                        <select class="form-control" name="student_birthorder" required>
                                            <option value="" disabled {{ old('student_birthorder') == null ? 'selected' : '' }}>- Birthorder -</option>
                                            <option value="1st" {{ old('student_birthorder') == '1st' ? 'selected' : '' }}>1st/Only Child</option>
                                            <option value="2nd" {{ old('student_birthorder') == '2nd' ? 'selected' : '' }}>2nd</option>
                                            <option value="3rd" {{ old('student_birthorder') == '3rd' ? 'selected' : '' }}>3rd</option>
                                            <option value="4th" {{ old('student_birthorder') == '4th' ? 'selected' : '' }}>4th</option>
                                            <option value="5th" {{ old('student_birthorder') == '5th' ? 'selected' : '' }}>5th</option>
                                            <option value="6th" {{ old('student_birthorder') == '6th' ? 'selected' : '' }}>6th</option>
                                            <option value="7th" {{ old('student_birthorder') == '7th' ? 'selected' : '' }}>7th</option>
                                            <option value="8th" {{ old('student_birthorder') == '8th' ? 'selected' : '' }}>8th</option>
                                            <option value="9th" {{ old('student_birthorder') == '9th' ? 'selected' : '' }}>9th</option>
                                            <option value="10th" {{ old('student_birthorder') == '10th' ? 'selected' : '' }}>10th</option>
                                            <option value="N/A" {{ old('student_birthorder') == 'N/A' ? 'selected' : '' }}>Not In The List</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-user rounded" name="student_address" value="{{ old('student_address') }}" placeholder="Address" required>
                            </div>
                            <div class="form-group">
                                <label>Hobbies & Activities</label>
                                <input type="text" class="form-control form-control-user rounded" name="student_hobbies" value="{{ old('student_hobbies') }}" placeholder="Hobbies & Activities">
                            </div>
                            <div class="form-group">
                                <label>Favorite Food or Treats</label>
                                <input type="text" class="form-control form-control-user rounded" name="student_favorite" value="{{ old('student_favorite') }}" placeholder="Favorite Food or Treats">
                            </div>
                        </div>
                    </div>
                    <!-- Tuition Fee Tab -->
                    <div class="tab-content border border-top-0 border-danger" id="nav-tabContent">
                        <div class="tab-pane fade show p-3" id="nav-tuition" role="tabpanel"
                            aria-labelledby="nav-tuition-tab">
                            <h3>Tuition Fee Information</h3>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <label>Tuition Amount <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-user rounded" name="tuition_amount" value="{{ old('tuition_amount') }}" placeholder="Amount" required>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <label for="discount">Discount</label>
                                        <select class="form-control" name="tuition_discount" id="discount">
                                            <option value="" disabled {{ old('tuition_discount') == null ? 'selected' : '' }}>- Select Discount -</option>
                                            <option value="Academic Discount" {{ old('tuition_discount') == 'Academic Discount' ? 'selected' : '' }}>Academic discounts</option>
                                            <option value="Sibling Discount" {{ old('tuition_discount') == 'Sibling Discount' ? 'selected' : '' }}>Sibling discounts</option>
                                            <option value="Parent Discount" {{ old('tuition_discount') == 'Parent Discount' ? 'selected' : '' }}>Parent-alumnus discounts</option>
                                            <option value="Early Discount" {{ old('tuition_discount') == 'Early Discount' ? 'selected' : '' }}>Early payment discounts</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <label for="custom_discount">Custom Discount Percentage (Optional)</label>
                                        <input type="number" class="form-control form-control-user rounded" name="custom_discount" id="custom_discount" value="{{ old('custom_discount') }}" placeholder="Enter custom discount percentage">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-end">
                                <button type="submit" class="btn btn-success btn-md">Submit</button>
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