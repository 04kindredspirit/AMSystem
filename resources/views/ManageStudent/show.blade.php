@php
    $totalBalance = $payments->sum('balance');
@endphp
<x-app>
@include('Components.navbar')
@section('title', 'Parent Directory')
<link href="{{ asset('admin_assets/vendor/font-awesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('codes-styles/student-show.css') }}" rel="stylesheet" href="style.css">
<script src="{{ asset('dataTable/js/jquery-3.7.1.js') }}"></script>
@if(auth()->user()->role != 'Teacher' && auth()->user()->role != 'Accountant')
<link href="{{ asset('admin_assets/css/image-upload-hover.css') }}" rel="stylesheet" type="text/css">
@endif

<body class="bg-gradient-light text-dark">
    <div class="container mb-4">
        <div class="row">
            <!-- profile section -->
            <div class="col-12 col-sm-6 col-md-4 mb-3">
                <div class="card o-hidden border-0 shadow-lg">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <h3 class="mb-0">Student Profile</h3>
                        </div>
                        <hr class="border-secondary">
                        <div class="card text-center" style="width: 18rem;">
                            <div class="align-items-center justify-content-center py-3 position-relative" style="background-color: #800000;">
                                <img src="{{ asset($students->image ?? 'admin_assets/img/undraw_profile.svg') }}" id="studentImage" 
                                    class="img-fluid mb-2 p-3 profile-image" 
                                    style="width: 200px; height: 200px; object-fit: cover; border-radius: 50%;" alt="Profile Image">
                                @if(auth()->user()->role != 'Teacher' && auth()->user()->role != 'Accountant')
                                <p class="text-white" style="font-size: 10px;">Click the image to change profile</p>
                                <input type="file" id="imageUpload" class="btn btn-primary w-75 w-md-100 btn-sm" accept="image/*" hidden>
                                @endif
                                <p class="card-text h5 text-light font-weight-bold">{{ $students->studentFirst_name }} {{ $students->studentMiddle_name }} {{ $students->studentLast_name }} {{ $students->studentName_ext }}</p>
                                <small class="card-text text-light font-weight-bold">Section: {{ $students->studentSection }}</small><br>
                                <small class="card-text text-light font-weight-bold">LRN: {{ $students->studentLRN }}</small>
                            </div>
                        </div>
                        <div class="card rounded-0" style="width: 18rem;">
                            <div class="card-body">
                                <h6 class="card-title text-primary">Tuition Fee Information</h6>
                                <p class="card-text" style="font-size: 14px;">
                                    <strong>Original Tuition Amount: </strong>{{ number_format($students->studentTuition_amount, 2) }}
                                </p>
                                <p class="card-text" style="font-size: 14px;">
                                    <strong>Discount Amount: </strong>{{ number_format($students->discountedTuition_amount, 2) }}
                                </p>
                                <p class="card-text" style="font-size: 14px;">
                                    <strong>Current Balance: </strong>
                                    @php
                                        $displayBalance = null;

                                        if ($payments->isNotEmpty()) {
                                            $latestPayment = $payments->sortByDesc('paymentDate')->first();
                                            $displayBalance = $latestPayment->balance;
                                        } else {
                                            if (isset($students->discountedTuition_amount)) {
                                                $displayBalance = $students->discountedTuition_amount;
                                            } else {
                                                $displayBalance = $students->studentTuition_amount;
                                            }
                                        }
                                    @endphp

                                    @if ($payments->isNotEmpty() && $displayBalance == 0)
                                        <span class="text-success">No balance (Ready for academic advancement)</span>
                                    @else
                                        {{ number_format($displayBalance) }}
                                    @endif
                                </p>
                                <p class="card-text" style="font-size: 14px;">
                                    <i class="fas fa-tags" style="font-size: 12px;"></i> 
                                    <strong>Discount Type: </strong>{{ $students->studentTuition_discount }}
                                </p>
                            </div>
                        </div>
                        <div class="card rounded-0" style="width: 18rem;">
                            <div class="card-body">
                                <h6 class="card-title text-primary">Personal Information</h6>
                                <p class="card-text" style="font-size: 14px;"><i class="fas fa-venus-mars" style="font-size: 14px;"></i> <strong>Gender:</strong> {{ $students->studentGender }}</p>
                                <p class="card-text" style="font-size: 14px;"><i class="fas fa-birthday-cake" style="font-size: 14px;"></i> <strong> Birthdate:</strong> {{ $students->studentBirthdate }}</p>
                                <p class="card-text" style="font-size: 14px;"><i class="fas fa-sort-numeric-down-alt" style="font-size: 14px;"></i> <strong> Birthorder:</strong> {{ $students->studentBirthorder }}</p>
                                <p class="card-text" style="font-size: 14px;"><i class="fas fa-map-marker-alt" style="font-size: 14px;"></i> <strong> Address:</strong> {{ $students->studentAddress }}</p>
                                <p class="card-text" style="font-size: 14px;"><i class="fas fa-palette" style="font-size: 14px;"></i> <strong>Hobbies:</strong>  {{ $students->studentHobby }}</p>
                                <p class="card-text" style="font-size: 14px;"><i class="fas fa-thumbs-up" style="font-size: 14px;"></i> <strong>Favorites:</strong>  {{ $students->studentFavorite }}</p>
                            </div>
                        </div>

                        <div class="card rounded-0" style="width: 18rem;">
                            <div class="card-body">
                                <h6 class="card-title text-primary">Parent/Guardian Information</h6>
                                @forelse($parents as $parent)
                                    <div class="mb-3">
                                        <p class="card-text" style="font-size: 14px;">
                                            <i class="fas fa-user-tie mr-2"></i>
                                            <strong>{{ $parent->parentRelationship_to_student }}: {{ $parent->parentFirst_name }} {{ $parent->parentLast_name }}</strong> 
                                        </p>
                                        <p class="card-text" style="font-size: 14px;">
                                            <i class="fas fa-phone mr-2"></i> {{ $parent->parentMobile_number }}
                                        </p>
                                        <p class="card-text" style="font-size: 14px;">
                                            <i class="fas fa-envelope mr-2"></i> {{ $parent->parentEmail }}
                                        </p>
                                        <p class="card-text" style="font-size: 14px;">
                                            <i class="fas fa-map-marker-alt mr-2"></i> {{ $parent->parentAddress }}
                                        </p>
                                    </div>
                                @empty
                                    <p class="text-muted">No parent/guardian information available.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="card rounded-0" style="width: 18rem;">
                            <div class="card-body">
                                <h6 class="card-title text-primary">Siblings Connection</h6>
                                @if($siblings->count() > 0)
                                    <ul class="list-group list-group-flush">
                                        @foreach($siblings as $sibling)
                                            <li class="list-group-item">
                                                <strong>{{ $sibling->studentFirst_name }} {{ $sibling->studentLast_name }}</strong>
                                                <ul>
                                                    <li class="list-unstyled"><strong>Section:</strong> {{ $sibling->studentSection }}</li>
                                                    <li class="list-unstyled"><strong>LRN:</strong> {{ $sibling->studentLRN }}</li>
                                                    <li class="list-unstyled"><strong>Gender:</strong> {{ $sibling->studentGender }}</li>
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted">No siblings linked.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- hero Image Section -->
            <div class="col-12 col-sm-6 col-md-8">
                <img src="{{ asset('admin_assets/img/360_F_223240002_6GAvK9B0uB4v5wGIkEiAUAOun4C4hwGd.jpg') }}" class="img-fluid rounded shadow" alt="Profile" style="width: 100%; height: 200px;">
                <div class="card text-dark text-center bg-light mb-3 rounded-top-0">
                    <div class="card-header">Payment Records</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            @if ($payments->isNotEmpty() && $latestPayment->balance == 0)
                                <div class="alert alert-success mb-4">
                                    <strong>Student has no balance, student is ready for academic advancement.</strong>
                                </div>
                            @endif
                            <table id="myTable" class="table table-striped table-hover" style="font-size: 12px;">
                                <thead class="table-primary text-center">
                                    <tr>
                                        <th>Date</th>
                                        <th>Payment OR</th>
                                        <th>Balance</th>
                                        <th>Amount Paid</th>
                                        <th>Payment Period</th>
                                        <th>Section</th>
                                        <th>Type</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @if($payments->count() > 0)
                                        @foreach($payments as $payment)
                                        <tr>
                                            <td>{{ $payment->paymentDate ?? '' }}</td>
                                            <td>{{ $payment->paymentOR ?? '' }}</td>
                                            <td>{{ number_format($payment->balance, 2) ?? '' }}</td>
                                            <td>{{ number_format($payment->paymentAmount, 2) ?? '' }}</td>
                                            <td>{{ $payment->paymentPeriod ?? '' }}</td>
                                            <td>{{ $payment->studentPayment_section ?? '' }}</td>
                                            <td>
                                                @if($payment->record_type === 'balance_adjustment')
                                                    Balance Reset
                                                @else
                                                    Payment
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="8" class="text-center">No payment records found</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="section-modal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal-title">Detailed Tuition Fee Information</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="receipt">
                                <div class="receipt-header">
                                    <img src="{{ asset('admin_assets/img/2.png') }}" class="img-fluid rounded shadow mx-3" alt="Responsive image" style="width: 40px; height: 40px;">
                                    <h1>MOTHER SHEPHERD ACADEMY OF VALENZUELA</h1>
                                    <p>93 Padrinao, St. Valenzuela, Metro Manita</p>
                                    <p>0925 601 7999</p>
                                </div>
                                <hr>
                                <table class="receipt-table">
                                    <tr>
                                        <th>Receipt No:</th>
                                        <th>Student Name:</th>
                                        <th>Date:</th>
                                    </tr>
                                    <tr>
                                        <td>{{ $payment->paymentOR ?? '' }}</td>
                                        <td>{{ $students->studentFirst_name }} {{ $students->studentMiddle_name }} {{ $students->studentLast_name }} {{ $students->studentName_ext }}</td>
                                        <td>{{ $payment->paymentDate ?? '' }}</td>
                                    </tr>
                                </table>
                                <table class="receipt-table">
                                    <tr>
                                        <th class="border-top-0">Section:</th>
                                        <th class="border-top-0">School Year:</th>
                                        <th class="border-top-0">Payment Period:</th>
                                        <th class="border-top-0">Teller's Name:</th>
                                    </tr>
                                    <tr>
                                        <td>{{ $students->studentSection }}</td>
                                        <td>{{ $students->school_year }}</td>
                                        <td>{{ $payment->paymentPeriod ?? '' }}</td>
                                        <td>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</td>
                                    </tr>
                                </table>
                                <table class="receipt-table">
                                    <tr>
                                        <th class="border-top-0">Balance: </th>
                                        <th class="border-top-0">Tuition Amount: </th>
                                        <th class="border-top-0">Amount in Words: </th>
                                    </tr>
                                    <tr>
                                        <td>{{ $payment->balance ?? '' }}</td>
                                        <td>{{ $payment->paymentAmount ?? ''}}</td>
                                        <td>One Hundred Million One Thousand Petot</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('admin_assets/js/bootstrap.bundle.min.js') }}"></script>
    @if(auth()->user()->role != 'Teacher' && auth()->user()->role != 'Accountant')
    <script>
        document.getElementById("studentImage").addEventListener("click", function () {
            document.getElementById("imageUpload").click();
        });

        document.getElementById("imageUpload").addEventListener("change", function (e) {
            var formData = new FormData();
            formData.append("image", e.target.files[0]);
            formData.append("student_id", "{{ $students->id }}");

            fetch("/upload-student-image", {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    document.getElementById("studentImage").src = data.imageUrl;
                } else {
                    alert("Image upload failed!");
                }
            })
            .catch((error) => {
                console.error("Error:", error);
            });
        });
    </script>
    @endif
    <script>
        $(document).ready(function () {
            $('#myTable tbody tr').on('click', function () {
                $('#section-modal').modal('show');
            });
        });
    </script>
    

</body>
</x-app>