<x-app>
@include('Components.navbar')
@section('title', 'Edit')
<link href="{{ asset('admin_assets/vendor/font-awesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
<body class="bg-gradient-light text-dark">
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="mb-0">Edit Student Information</h3>
                    <a href="{{ route('ManageStudent/StudentDirectory') }}" class="close" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <hr class="border-secondary">
                <form method="POST" action="{{ route('ManageStudent.update', $students->id) }}">
                    @csrf
                    @method('PUT')
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
                                <div class="row">
                                    <div class="col-8">
                                        <label for="studentLRN">LRN <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-user rounded" name="studentLRN" id="studentLRN" value="{{ $students->studentLRN }}">
                                    </div>
                                    <div class="col-4">
                                        <label for="studentSection">Section <span class="text-danger">*</span></label>
                                        <select class="form-control" name="studentSection" id="studentSection">
                                            <option value="" disabled {{ old('studentSection', $students->studentSection) == null ? 'selected' : '' }}>- Select Section -</option>
                                            @foreach($section as $sections)
                                                <option value="{{ $sections->section_name }}" {{ old('studentSection', $students->studentSection) == $sections->section_name ? 'selected' : '' }}>
                                                    {{ $sections->section_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="studentName_ext">Name Ext. (Sr., Jr.)</label>
                                        <input type="text" class="form-control form-control-user rounded" name="studentName_ext" id="studentName_ext" value="{{ $students->studentName_ext }}">
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="form-group">
                                        <label for="studentFirst_name">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-user rounded" name="studentFirst_name" id="studentFirst_name" value="{{ $students->studentFirst_name }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="studentMiddle_name">Middle Name</label>
                                        <input type="text" class="form-control form-control-user rounded" name="studentMiddle_name" id="studentMiddle_name" value="{{ $students->studentMiddle_name }}">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="studentLast_name">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-user rounded" name="studentLast_name" id="studentLast_name" value="{{ $students->studentLast_name }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="studentGender">Gender <span class="text-danger">*</span></label>
                                        <select class="form-control" name="studentGender" id="studentGender">
                                            <option {{ old('studentGender', $students->studentGender) == null ? 'selected' : '' }}>- Select Gender -</option>
                                            <option value="Male" {{ old('studentGender', $students->studentGender) == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ old('studentGender', $students->studentGender) == 'Female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="studentBirthdate">Birthdate <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control form-control-user rounded" name="studentBirthdate" id="studentBirthdate" value="{{ $students->studentBirthdate }}">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="studentBirthorder">Birthorder</label>
                                        <select class="form-control" name="studentBirthorder" id="studentBirthorder">
                                            <option {{ old('studentBirthorder', $students->studentBirthorder) == null ? 'selected' : '' }}>- Birthorder -</option>
                                            <option value="1st" {{ old('studentBirthorder', $students->studentBirthorder) == '1st' ? 'selected' : '' }}>1st/Only Child</option>
                                            <option value="2nd" {{ old('studentBirthorder', $students->studentBirthorder) == '2nd' ? 'selected' : '' }}>2nd</option>
                                            <option value="3rd" {{ old('studentBirthorder', $students->studentBirthorder) == '3rd' ? 'selected' : '' }}>3rd</option>
                                            <option value="4th" {{ old('studentBirthorder', $students->studentBirthorder) == '4th' ? 'selected' : '' }}>4th</option>
                                            <option value="5th" {{ old('studentBirthorder', $students->studentBirthorder) == '5th' ? 'selected' : '' }}>5th</option>
                                            <option value="6th" {{ old('studentBirthorder', $students->studentBirthorder) == '6th' ? 'selected' : '' }}>6th</option>
                                            <option value="7th" {{ old('studentBirthorder', $students->studentBirthorder) == '7th' ? 'selected' : '' }}>7th</option>
                                            <option value="8th" {{ old('studentBirthorder', $students->studentBirthorder) == '8th' ? 'selected' : '' }}>8th</option>
                                            <option value="9th" {{ old('studentBirthorder', $students->studentBirthorder) == '9th' ? 'selected' : '' }}>9th</option>
                                            <option value="10th" {{ old('studentBirthorder', $students->studetnBirthorder) == '10th' ? 'selected' : '' }}>10th</option>
                                            <option value="N/A" {{ old('studentBirthorder', $students->studentBirthorder) == 'N/A' ? 'selected' : '' }}>Not In The List</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="studentAddress">Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-user rounded" name="studentAddress" id="studentAddress" value="{{ $students->studentAddress }}">
                            </div>
                            <div class="form-group">
                                <label for="studentHobby">Hobbies & Activities</label>
                                <input type="text" class="form-control form-control-user rounded" name="studentHobby" id="studentHobby" value="{{ $students->studentHobby }}">
                            </div>
                            <div class="form-group">
                                <label for="studentFavorite">Favorite Food or Treats</label>
                                <input type="text" class="form-control form-control-user rounded" name="studentFavorite" id="studentFavorite" value="{{ $students->studentFavorite }}">
                            </div>
                        </div>
                        <div class="card-header bg-danger text-white text-center">
                                Tuition Fee Information
                        </div>
                        <div class="form-group p-3">
                            <div class="row">
                                <div class="col-12 col-sm-6 col-md-4">
                                    <label for="studentTuition_amount">Tuition Amount <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control form-control-user rounded" name="studentTuition_amount" id="studentTuition_amount" value="{{ $students->studentTuition_amount }}">
                                </div>
                                <div class="col-12 col-sm-6 col-md-4">
                                    <label for="studentTuition_discount">Discount</label>
                                    <select class="form-control" name="studentTuition_discount" id="studentTuition_discount">
                                        <option value="" disabled {{ is_null($students->studentTuition_discount) ? 'selected' : ''}}>
                                            - Select Discount -
                                        </option>
                                        @foreach($activeDiscounts as $discount)
                                            <option value="{{ $discount->discount_type }}" 
                                                    data-percentage="{{ $discount->percentage }}" 
                                                    {{ $students->studentTuition_discount == $discount->discount_type ? 'selected' : '' }}>
                                                {{ $discount->discount_type }} ({{ $discount->percentage }}%)
                                            </option>
                                        @endforeach
                                        <option value="Custom Discount" {{ $students->studentTuition_discount == 'Custom Discount' ? 'selected' : '' }}>
                                            Custom Discount
                                        </option>
                                    </select>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4">
                                    <label for="custom_discount">Discount Percentage</label>
                                    <input type="number" class="form-control form-control-user rounded" 
                                        name="custom_discount" id="custom_discount" 
                                        value="{{ $students->studentTuition_discount == 'Custom Discount' ? $students->discountPercentage : '' }}"
                                        placeholder="Enter percentage" 
                                        {{ $students->studentTuition_discount != 'Custom Discount' ? 'disabled' : '' }}>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-center">
                            <a href="{{ route('ManageStudent/StudentDirectory') }}" class="btn btn-danger">Close</a>
                            <button class="btn btn-primary">Save</button>
                        </div> 
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('admin_assets/js/bootstrap.bundle.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            function initializeDiscountFields() {
                const discountType = $('#studentTuition_discount').val();
                const customDiscountInput = $('#custom_discount');

                if (discountType === 'Custom Discount') {
                    customDiscountInput.prop('disabled', false);
                } else if (discountType && discountType !== 'No Discount') {
                    const percentage = $('#studentTuition_discount option:selected').data('percentage');
                    customDiscountInput.val(percentage);
                    customDiscountInput.prop('disabled', true);
                } else {
                    customDiscountInput.val('');
                    customDiscountInput.prop('disabled', true);
                }
            }

            $('#studentTuition_discount').change(function() {
                const selectedOption = $(this).find('option:selected');
                const discountPercentage = selectedOption.data('percentage');
                const customDiscountInput = $('#custom_discount');

                if (selectedOption.val() === 'Custom Discount') {
                    customDiscountInput.prop('disabled', false);
                    customDiscountInput.val('');
                    customDiscountInput.focus();
                } else if (discountPercentage !== undefined) {
                    customDiscountInput.val(discountPercentage);
                    customDiscountInput.prop('disabled', true);
                } else {
                    customDiscountInput.val('');
                    customDiscountInput.prop('disabled', true);
                }
            });

            initializeDiscountFields();
        });
    </script>


</body>
</x-app>