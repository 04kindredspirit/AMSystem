<x-app>
@include('Components.navbar')
@section('title', 'Academic Advancement')
<link href="{{ asset('admin_assets/vendor/font-awesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('codes-styles/academic-adv.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('dataTable/css/dataTables.bootstrap5.css') }}">

<script src="{{ asset('dataTable/js/jquery-3.7.1.js') }}"></script>

<script defer src="{{ asset('dataTable/js/dataTables.js') }}"></script>
<script defer src="{{ asset('dataTable/js/dataTables.bootstrap5.js') }}"></script>
<script defer src="{{ asset('dataTable/js/script.js') }}"></script>

<meta name="csrf-token" content="{{ csrf_token() }}">
<body class="bg-gradient-light text-dark">
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="mb-0">Academic Advancement</h3>
                </div>
                <hr class="border-secondary">
                <div class="alert alert-warning d-flex flex-row" role="alert1">
                    <div class="d-flex align-items-center mr-2">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                    </div>
                    <div>
                        <strong>IMPORTANT NOTICE</strong>
                        <div class="text">
                        Before making any changes to the student level, please ensure that a new school year record has been created and is active.
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
                
                <div class="table-responsive table-sm">
                    <table id="AcademicTable" class="table table-striped table-hover" style="width:100%">
                        <thead class="table-primary">
                            <tr>
                                <th class="text-center" width="60%">Name</th>
                                <th class="text-center" width="40%">Section</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @foreach ($studentsWithZeroBalance as $payment)
                                @if($payment->student)
                                    <tr data-id="{{ $payment->student->id }}" 
                                        data-name="{{ $payment->studentFullname }}" 
                                        data-section="{{ $payment->student->studentSection ?? 'N/A' }}">
                                        <td>{{ $payment->studentFullname }}</td>
                                        <td>{{ $payment->student->studentSection ?? 'N/A' }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="modal fade" id="section-modal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modal-title">Student Details</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('academic.advancement.update') }}">
                                    @csrf
                                    <!-- hidden student id input field -->
                                    <input type="hidden" name="student_id" id="student_id" value="">
                                    
                                    <div class="card border border-top-0 border-danger">
                                        <div class="card-header bg-danger text-white text-center">
                                            Student Information
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group row">
                                                <div class="col-12 col-sm-6 col-md-12">
                                                    <label>Student Name</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control form-control-user rounded" name="student_name" id="student_name" required readonly>
                                                    </div>
                                                </div>
                                                <!-- <div class="col-12 col-sm-6 col-md-6">
                                                    <label>OR No.</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control form-control-user rounded" name="academic_or" id="academic_or" placeholder="OR No." required>
                                                    </div>
                                                </div> -->
                                            </div>
                                            <div class="row form-group">
                                                <div class="col-12 col-sm-6 col-md-6">
                                                    <label><p></p></label>
                                                    <div class="input-group">
                                                        <label class="input-group-text">From Section</label>
                                                        <input type="text" class="form-control form-control-user rounded-start-0" name="from_section" id="from_section" value="{{ $payment->student->studentSection ?? 'N/A' }}" required readonly>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-6">
                                                    <label><p></p></label>
                                                    <div class="input-group">
                                                        <label class="input-group-text">Transfer to</label>
                                                        <select class="form-select" name="transfer_to_section" id="inputGroupSelect02">
                                                            <option value="" disabled {{ old('student_section') == null ? 'selected' : '' }}>- Select Section -</option>
                                                            @foreach($section as $sections)
                                                                <option value="{{ $sections->section_name }}" {{ old('student_section') == $sections->section_name ? 'selected' : '' }}>
                                                                    {{ $sections->section_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>School Year <span class="text-danger">*</span></label>
                                                <select class="form-control" name="school_year" required>
                                                    <option value="" disabled {{ old('school_year') == null ? 'selected' : '' }}>- Select School Year -</option>
                                                    @foreach($schoolyear as $sy)
                                                        <option value="{{ $sy->school_year }}" {{ old('school_year') == $sy->school_year ? 'selected' : '' }}>
                                                            {{ $sy->school_year }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="card-header bg-danger text-white text-center">
                                            Tuition Fee Information
                                        </div>
                                        <div class="form-group row p-3">
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <label>Tuition Amount <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-user rounded" name="tuition_amount" value="{{ old('tuition_amount') }}" placeholder="Amount" required>
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
                                            <button type="button" data-bs-dismiss="modal" class="btn btn-danger btn-sm">Close</button>
                                            <div class="p-2">
                                                @if (auth()->user()->role != 'Teacher')
                                                    <button class="btn btn-primary btn-sm">
                                                    <i class="far fa-check-square"></i>
                                                    Process Advancement</button>
                                                @endif
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('admin_assets/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#AcademicTable').DataTable({
                order: [],
                layout: {
                    bottomStart: "pageLength",
                },
                language: {
                    emptyTable: "No students with zero balance found."
                }
            });

            $('#AcademicTable tbody').on('click', 'tr', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const section = $(this).data('section');

                $('#student_id').val(id);
                $('#student_name').val(name);
                $('#from_section').val(section);
                $('#section-modal').modal('show');
            });

            $('#section-modal').on('hidden.bs.modal', function() {
                $(this).find('form')[0].reset();
            });
        });
    </script>

    <script>
        $(document).ready(function() {
    // initializtion on discount field
    function initializeDiscountFields() {
        const discountType = $('#discountSelect').val();
        const customDiscountInput = $('#custom_discount');

        if (discountType === 'Custom Discount') {
            customDiscountInput.prop('disabled', false);
        } else if (discountType && discountType !== 'No Discount') {
            const percentage = $('#discountSelect option:selected').data('percentage');
            customDiscountInput.val(percentage);
            customDiscountInput.prop('disabled', true);
        } else {
            customDiscountInput.val('');
            customDiscountInput.prop('disabled', true);
        }
    }

    // this code handle the discount selection change
    $('#discountSelect').change(function() {
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

    // initialize the page on load
    initializeDiscountFields();
});
    </script>

</body>
</x-app>