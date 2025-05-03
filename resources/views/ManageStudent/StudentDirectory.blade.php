<x-app>
@include('Components.navbar')
@section('title', 'Student Directory')
<link href="{{ asset('admin_assets/vendor/font-awesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="{{ asset('dataTable/css/dataTables.bootstrap5.css') }}">

<script defer src="{{ asset('dataTable/js/jquery-3.7.1.js') }}"></script>
<script defer src="{{ asset('dataTable/js/bootstrap.bundle.min.js') }}"></script>
<script defer src="{{ asset('dataTable/js/dataTables.js') }}"></script>
<script defer src="{{ asset('dataTable/js/dataTables.bootstrap5.js') }}"></script>
<script defer src="{{ asset('dataTable/js/script.js') }}"></script>

<meta name="csrf-token" content="{{ csrf_token() }}">
<body class="bg-gradient-light text-dark">
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="mb-0">Student Directory</h3>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#reg-modal">+ Add New Student</button>
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
                <div class="table-responsive table-sm">
                    <table id="myTable" class="table table-striped table-hover text-center" style="min-width: 1000px;">
                        <thead class="table-primary">
                            <tr>
                                <th class="text-center"></th>
                                <th class="text-center">LRN</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Section</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-dark">
                            @if ($students->isNotEmpty()) 
                                @foreach ($students as $student)
                                    <tr>
                                        <td><img src="{{ asset($student->image ?? 'admin_assets/img/undraw_profile.svg') }}" 
                                                class="img-fluid rounded-circle mx-auto d-block" 
                                                style="width: 35px; height: 35px; object-fit: cover;" 
                                                alt="Profile Image">
                                        </td>
                                        <td>{{ $student->studentLRN ?? '' }}</td>
                                        <td>{{ $student->studentFirst_name ?? '' }} {{ $student->studentMiddle_name ?? '' }} {{ $student->studentLast_name ?? '' }} {{ $student->studentName_ext ?? '' }}</td>
                                        <td>{{ $student->studentSection ?? '' }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('ManageStudent.show', $student->id) }}" type="button" class="btn btn-secondary" style="font-size: 12px;"><i class="fas fa-eye"></i> View</a>
                                                @if(auth()->user()->role != 'Teacher' && auth()->user()->role !='Accountant')
                                                <a href="{{ route('ManageStudent.edit', $student->id) }}" type="button" class="btn btn-warning my-1" style="font-size: 12px;"><i class="fas fa-edit"></i> Edit</a>
                                                @endif
                                                <!-- button t o trigger modal -->
                                                @if(auth()->user()->role != 'Teacher' && auth()->user()->role !='Accountant' && auth()->user()->role !='SuperAdmin')
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $student->id }}" style="font-size: 10px;">
                                                Remove
                                                </button>
                                                <!-- remove modal -->
                                                <div class="modal fade" id="deleteModal{{ $student->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                                                <form action="{{ route('students.destroy', $student->id) }}" method="POST">
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

                <!--modal -->
                <div class="modal fade" id="reg-modal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modal-title">Add New Student</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('ManageStudent.save') }}">
                                    @csrf
                                    <div class="card border border-top-0 border-danger">
                                        <div class="card-header bg-danger text-white text-center">
                                            Student Information
                                        </div>
                                        <div class="card-body">
                                            <div class="alert alert-warning" role="alert">
                                                If information is unavailable, please type "N/A" in the field.
                                                <div>
                                                    <small>Please ensure that all fields marked with a red asterisk <span class="text-danger">(*)</span> are completed, as they are required.</small>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="linkToStudent">Link to enrolled student<span class="text-danger">*</span></label>
                                                <select class="form-control" name="link_to_student" id="linkToStudent" required>
                                                    <option value="" disabled {{ old('link_to_student') == null ? 'selected' : '' }}>- Select Student -</option>
                                                    @php
                                                        $groupedStudentsMap = [];

                                                        foreach ($groupedStudents as $group_id => $group) {
                                                            $groupedStudentsMap[$group_id] = $group;
                                                        }

                                                        $studentsMap = [];
                                                        foreach ($students as $student) {
                                                            $studentsMap[$student->id] = $student->studentFirst_name . ' ' . $student->studentLast_name;
                                                        }
                                                    @endphp

                                                    @foreach ($groupedStudentsMap as $group_id => $group)
                                                        @php
                                                            $groupNames = implode('], [', $group);
                                                            $groupNames = '[' . $groupNames . ']';
                                                        @endphp

                                                        <option value="{{ $group_id }}" {{ old('link_to_student') == $group_id ? 'selected' : '' }}>
                                                            {{ $groupNames }}
                                                        </option>
                                                    @endforeach

                                                    <!-- display student even if they're not part of a group or link -->
                                                    @foreach($students as $student)
                                                        @php
                                                            $isGrouped = false;
                                                            foreach ($groupedStudents as $group_id => $group) {
                                                                if (in_array($student->studentFirst_name . ' ' . $student->studentLast_name, $group)) {
                                                                    $isGrouped = true;
                                                                    break;
                                                                }
                                                            }
                                                        @endphp

                                                        @if (!$isGrouped)
                                                        <option value="{{ $student->id }}" {{ old('link_to_student') == $student->id ? 'selected' : '' }}>
                                                            {{ $student->studentFirst_name . ' ' . $student->studentLast_name }}
                                                        </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-12 col-sm-6 col-md-4">
                                                        <label>LRN <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control form-control-user rounded" name="studentLrn" placeholder="LRN" required>
                                                    </div>
                                                    <div class="col-12 col-sm-6 col-md-4">
                                                        <label for="section">Section <span class="text-danger">*</span></label>
                                                        <select class="form-control" name="studentSection" required>
                                                            <option value="" disabled {{ old('studentSection') == null ? 'selected' : '' }}>- Select Section -</option>
                                                            @foreach($section as $sections)
                                                                <option value="{{ $sections->section_name }}" {{ old('studentSection') == $sections->section_name ? 'selected' : '' }}>
                                                                    {{ $sections->section_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-12 col-sm-6 col-md-4">
                                                        <label>School Year <span class="text-danger">*</span></label>
                                                        <select class="form-control" name="school_year">
                                                            <option value="" disabled {{ old('school_year') == null ? 'selected' : '' }}>- Select School Year -</option>
                                                            @foreach($schoolyear as $sy)
                                                                <option value="{{ $sy->school_year }}" {{ old('school_year') == $sy->school_year ? 'selected' : '' }}>
                                                                    {{ $sy->school_year }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-sm-6 col-md-2">
                                                    <div class="form-group">
                                                        <label>Name Ext. (Sr., Jr.)</label>
                                                        <input type="text" class="form-control form-control-user rounded" name="studentNameExt" value="{{ old('studentNameExt') }}" placeholder="Name Ext. (Sr., Jr.)">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <label>First Name <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control form-control-user rounded" name="studentFname" value="{{ old('studentFname') }}" placeholder="First Name" required>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-3">
                                                    <div class="form-group">
                                                        <label>Middle Name</label>
                                                        <input type="text" class="form-control form-control-user rounded" name="studentMname" value="{{ old('studentMname') }}" placeholder="Middle Name">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-3">
                                                    <div class="form-group">
                                                        <label>Last Name <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control form-control-user rounded" name="studentLname" value="{{ old('studentLname') }}" placeholder="Last Name" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <label for="gender">Gender <span class="text-danger">*</span></label>
                                                        <select class="form-control" name="studentGender" required>
                                                            <option value="" disabled {{ old('studentGender') == null ? 'selected' : '' }}>- Select Gender -</option>
                                                            <option value="Male" {{ old('studentGender') == 'Male' ? 'selected' : '' }}>Male</option>
                                                            <option value="Female" {{ old('studentGender') == 'Female' ? 'selected' : '' }}>Female</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <label>Birthdate <span class="text-danger">*</span></label>
                                                        <input type="date" class="form-control form-control-user rounded" name="studentBday" value="{{ old('studentBday') }}" placeholder="Birthdate" required>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <label>Birthorder</label>
                                                        <select class="form-control" name="studentBo" required>
                                                            <option value="" disabled {{ old('studentBo') == null ? 'selected' : '' }}>- Birthorder -</option>
                                                            <option value="1st" {{ old('studentBo') == '1st' ? 'selected' : '' }}>1st/Only Child</option>
                                                            <option value="2nd" {{ old('studentBo') == '2nd' ? 'selected' : '' }}>2nd</option>
                                                            <option value="3rd" {{ old('studentBo') == '3rd' ? 'selected' : '' }}>3rd</option>
                                                            <option value="4th" {{ old('studentBo') == '4th' ? 'selected' : '' }}>4th</option>
                                                            <option value="5th" {{ old('studentBo') == '5th' ? 'selected' : '' }}>5th</option>
                                                            <option value="6th" {{ old('studentBo') == '6th' ? 'selected' : '' }}>6th</option>
                                                            <option value="7th" {{ old('studentBo') == '7th' ? 'selected' : '' }}>7th</option>
                                                            <option value="8th" {{ old('studentBo') == '8th' ? 'selected' : '' }}>8th</option>
                                                            <option value="9th" {{ old('studentBo') == '9th' ? 'selected' : '' }}>9th</option>
                                                            <option value="10th" {{ old('studentBo') == '10th' ? 'selected' : '' }}>10th</option>
                                                            <option value="N/A" {{ old('studentBo') == 'N/A' ? 'selected' : '' }}>Not In The List</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Address <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-user rounded" name="studentAdd" value="{{ old('studentAdd') }}" placeholder="Address" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Hobbies & Activities</label>
                                                <input type="text" class="form-control form-control-user rounded" name="studentHobbies" value="{{ old('studentHobbies') }}" placeholder="Hobbies & Activities">    
                                            </div>
                                            <div class="form-group">
                                                <label>Favorite Food or Treats</label>
                                                <input type="text" class="form-control form-control-user rounded" name="studentFavorites" value="{{ old('studentFavorites') }}" placeholder="Favorite Food or Treats">
                                            </div>
                                        </div>
                                        <div class="card-header bg-danger text-white text-center">
                                                Tuition Fee Information
                                        </div>
                                        <div class="form-group row p-3">
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <label>Tuition Amount <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-user rounded" name="tuitionAmount" value="{{ old('tuitionAmount') }}" placeholder="Amount" required>
                                            </div>

                                            <div class="col-12 col-sm-6 col-md-4">
                                                <label for="discount">Discount</label>
                                                <select class="form-control" name="studentDisc" id="discountSelect">
                                                    <option value="" disabled {{ empty(old('studentDisc')) ? 'selected' : '' }}>- Select Discount -</option>
                                                    @foreach($activeDiscounts as $discount)
                                                        <option value="{{ $discount->discount_type }}" 
                                                                data-percentage="{{ $discount->percentage }}" 
                                                                {{ old('studentDisc') === $discount->discount_type ? 'selected' : '' }}>
                                                            {{ $discount->discount_type }} ({{ $discount->percentage }}%)
                                                        </option>
                                                    @endforeach
                                                    <option value="Custom Discount" {{ old('studentDisc') === 'Custom Discount' ? 'selected' : '' }}>
                                                        Custom Discount
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="col-12 col-sm-6 col-md-4">
                                                <label for="custom_discount">Discount Percentage</label>
                                                <input type="number" class="form-control form-control-user rounded" 
                                                    name="custom_discount" id="custom_discount" 
                                                    value="{{ old('custom_discount') }}"
                                                    placeholder="Enter percentage" 
                                                    disabled>
                                            </div>
                                        </div>
                                        <div class="modal-footer d-flex justify-content-center">
                                            <button type="button" data-bs-dismiss="modal" class="btn btn-danger">Close</button>
                                            <button class="btn btn-primary">Save</button>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('admin_assets/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const dropdown = document.getElementById("linkToStudent");
            if (dropdown) {
                dropdown.addEventListener("change", function () {
                    // console.log("Dropdown changed!");

                    var selectedStudentId = this.value;
                    fetch("/update-linked-students", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        },
                        body: JSON.stringify({ student_id: selectedStudentId }),
                    })
                        .then((response) => response.json())
                        .then((data) => {
                            dropdown.innerHTML = "<option>- Select Student -</option>";

                            data.linkedStudents.forEach((student) => {
                                const option = document.createElement("option");
                                option.value = student.id;
                                option.text = `${student.studentFirst_name} ${student.studentLast_name}`;
                                dropdown.appendChild(option);
                            });
                        })
                        .catch((error) => console.error("Error:", error));
                });
            } else {
                console.error("Dropdown element not found!");
            }
        });
    </script>

    <script>
        $(document).ready(function() {

            function updateCustomDiscountField() {
                const discountSelect = $('#discountSelect');
                const customDiscount = $('#custom_discount');
                const selectedValue = ($.trim(discountSelect.val()) || '').toLowerCase();

                if (selectedValue === 'custom discount') {
                    customDiscount.prop('disabled', false);
                    customDiscount.val('');  // Clear on selection
                    customDiscount.focus();
                } else {
                    const selectedOption = discountSelect.find('option:selected');
                    const percentage = selectedOption.data('percentage');

                    customDiscount.val(percentage || '');
                    customDiscount.prop('disabled', true);
                }
            }

            // On page load
            updateCustomDiscountField();

            // On dropdown change
            $('#discountSelect').change(function() {
                updateCustomDiscountField();
            });

            // Restrict custom discount input to 0–100
            $('#custom_discount').on('input', function() {
                let value = parseFloat($(this).val());
                if (value > 100) {
                    $(this).val(100);
                } else if (value < 0) {
                    $(this).val(0);
                }
            });
        });
    </script>

</body>
</x-app>