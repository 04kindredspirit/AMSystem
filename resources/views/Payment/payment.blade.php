<x-app>
@include('Components.navbar')
@section('title', 'Tuition Payment')
<link href="{{ asset('admin_assets/vendor/font-awesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
<body class="bg-gradient-light text-dark">
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="mb-0">Manage Tuition</h3>
                </div>
                <hr class="border-secondary">
                <div class="card border border-top-0 border-danger">
                    <div class="card-header bg-danger text-white text-center">
                        Tuition Payment
                    </div>
                    <div class="card-body">
                    <p>If information is unavailable, please type "N/A" in the field.</p>
                        <div class="form-group mb-5">
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
                            <label class="ml-3">Enter Sudent LRN, First Name or Last Name</label>
                            <div class="input-group mb-3 col-12 col-sm-6 col-md-4">
                                <input type="text" class="form-control text-center" id="search_query" placeholder="Search Student" aria-label="searchText" aria-describedby="basic-addon1">
                                <div class="input-group-prepend">
                                    <span class="input-group-text rounded-right" id="basic-addon1"><i class="fas fa-search"></i></span>
                                </div>
                            </div>

                            <div id="noStudentAlert" class="alert alert-danger alert-dismissible fade show d-none" role="alert">
                                No student found.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="closeAlert()">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                        <form action="{{ route('Payment') }}" method="POST">
                            @csrf
                            <div class="row d-flex justify-content-between">
                                <div class="col-12 col-sm-6 col-4">
                                    <div class="form-group text-center">
                                        <label>Date</label>
                                        <input type="date" class="form-control form-control-user rounded text-center" id="todaydate" name="paymentDate" required readonly>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-4">
                                    <div class="form-group text-center">
                                        <label>Receipt No.</label>
                                        <input type="text" class="form-control form-control-user rounded text-center" id="paymentReceipt" name="paymentReceipt" placeholder="OR No." required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <label>Student Name</label>
                                <input type="text" class="form-control form-control-user rounded text-center" id="paymentFname" name="paymentFname" placeholder="Student's Full Name" required readOnly>
                            </div>
                            <div class="form-group text-center">
                                <label>Student LRN</label>
                                <input type="text" class="form-control form-control-user rounded text-center" id="paymentLrn" name="paymentLrn" placeholder="LRN" required readOnly>
                            </div>
                            <div class="form-group text-center">
                                <label>Payment Amount</label>
                                <input type="text" class="form-control form-control-user rounded text-center" id="paymentAmount" name="paymentAmount" placeholder="Amount" required>
                            </div>
                            <div class="row form-group text-center">
                                <div class="col-12 col-sm-6 col-md-6">
                                    <label>Section</label>
                                    <input type="text" class="form-control form-control-user rounded text-center" id="paymentDiscount" name="studentPayment_section" placeholder="Section" required readOnly>
                                </div>
                                <div class="col-12 col-sm-6 col-md-6">
                                    <label>Balance Tuition</label>
                                    <input type="text" class="form-control form-control-user rounded text-center" id="tuitionAmount" name="tuitionAmount" placeholder="Amount" required readOnly>
                                </div>
                            </div>
                            <div class="form-group">
                                <select class="form-control text-center" name="paymentPeriod">
                                    <option selected>- Transaction Period -</option>
                                    <option value="1st Period">1st Period</option>
                                    <option value="2nd Period">2nd Period</option>
                                    <option value="3rd Period">3rd Period</option>
                                    <option value="4th Period">4th Period</option>
                                    <option value="Remaining Balance">Remaining Balance</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-success btn-md btn-block">Proceed Transaction</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('admin_assets/js/bootstrap.bundle.min.js') }}"></script>

    <!-- date -->
    <script>
        document.getElementById("todaydate").value = new Date().toISOString().split('T')[0];
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- payment validation -->
    <script src="{{ asset('codes-js/payment-period-validation.js') }}"></script>

    <!-- payment period validation -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const paymentPeriodSelect = document.querySelector(
                'select[name="paymentPeriod"]'
            );
            const paymentLrnInput = document.getElementById("paymentLrn");
            const form = document.querySelector("form");

            function showError(message) {
                const existingError = document.querySelector(".payment-period-error");
                if (existingError) {
                    existingError.remove();
                }

                const errorDiv = document.createElement("div");
                errorDiv.className =
                    "payment-period-error alert-danger border border-danger rounded text-center mt-2 p-2";
                errorDiv.textContent = message;

                paymentPeriodSelect.insertAdjacentElement("afterend", errorDiv);
            }

            function clearError() {
                const existingError = document.querySelector(".payment-period-error");
                if (existingError) {
                    existingError.remove();
                }
            }

            paymentPeriodSelect.addEventListener("change", function () {
                const selectedPeriod = paymentPeriodSelect.value;
                const studentLrn = paymentLrnInput.value;

                // skip validation for remaining balance
                if (selectedPeriod === "Remaining Balance") {
                    clearError();
                    return;
                }

                if (selectedPeriod && studentLrn) {
                    $.ajax({
                        url: "{{ route('check.payment.period') }}",
                        method: "GET",
                        data: {
                            studentLrn: studentLrn,
                            paymentPeriod: selectedPeriod,
                        },
                        success: function (response) {
                            if (response.alreadyPaid) {
                                showError(
                                    "This student has already paid for the selected transaction period."
                                );
                                paymentPeriodSelect.value = "- Transaction Period -";
                            } else {
                                clearError();
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error("Error checking payment period:", error);
                        },
                    });
                } else {
                    clearError();
                }
            });

            form.addEventListener("submit", function (event) {
                const selectedPeriod = paymentPeriodSelect.value;
                const studentLrn = paymentLrnInput.value;

                if (selectedPeriod === "- Transaction Period -" || !selectedPeriod) {
                    showError(
                        "Please select a valid transaction period before proceeding."
                    );
                    event.preventDefault();
                    return;
                }

                if (selectedPeriod === "Remaining Balance") {
                    clearError();
                    return;
                }

                if (selectedPeriod && studentLrn) {
                    $.ajax({
                        url: "{{ route('check.payment.period') }}",
                        method: "GET",
                        async: false,
                        data: {
                            studentLrn: studentLrn,
                            paymentPeriod: selectedPeriod,
                        },
                        success: function (response) {
                            if (response.alreadyPaid) {
                                showError(
                                    "This student has already paid for the selected transaction period."
                                );
                                event.preventDefault();
                            } else {
                                clearError();
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error("Error checking payment period:", error);
                        },
                    });
                }
            });
        });
    </script>

    <!-- payment search -->
    <script>
        $(document).ready(function() {
            $('#search_query').on('keyup', function() {
                var searchQuery = $(this).val();

                if (searchQuery.length > 0) {
                    $.ajax({
                        url: "{{ route('student.search.ajax') }}",
                        method: 'GET',
                        data: { search_query: searchQuery },
                        success: function(response) {
                            if (response.error) {
                                $('#noStudentAlert').removeClass('d-none').text(response.error);
                            } else if (response) {
                                $('#noStudentAlert').addClass('d-none');
                                $('#paymentFname').val(response.full_name || '');
                                $('#paymentLrn').val(response.studentLRN || '');
                                $('#paymentAmount').val(response.paymentAmount || '');
                                $('#paymentDiscount').val(response.studentSection || '');
                                $('#tuitionAmount').val(response.balance || '');
                            } else {
                                showNoStudentAlert();
                            }
                        },
                        error: function(xhr, status, error) {
                            showNoStudentAlert();
                        }
                    });
                } else {
                    $('#paymentFname, #paymentLrn, #paymentAmount, #paymentDiscount, #tuitionAmount').val('');
                    $('#noStudentAlert').addClass('d-none');
                }
            });

            function showNoStudentAlert() {
                $('#noStudentAlert').removeClass('d-none'); 
            }

            window.closeAlert = function() {
                $('#noStudentAlert').addClass('d-none');
            };
        });
    </script>

</body>
</x-app>