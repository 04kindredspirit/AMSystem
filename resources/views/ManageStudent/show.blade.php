@php
    $totalBalance = $payments->sum('balance');
@endphp
<x-app>
@include('Components.navbar')
@section('title', 'Parent Directory')
<link href="{{ asset('admin_assets/vendor/font-awesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="{{ asset('dataTable/css/dataTables.bootstrap5.css') }}">
<link href="{{ asset('codes-styles/student-show.css') }}" rel="stylesheet" href="style.css">

<script src="{{ asset('dataTable/js/jquery-3.7.1.js') }}"></script>
<script defer src="{{ asset('dataTable/js/dataTables.js') }}"></script>
<script defer src="{{ asset('dataTable/js/dataTables.bootstrap5.js') }}"></script>
<script defer src="{{ asset('dataTable/js/script.js') }}"></script>
@if(auth()->user()->role != 'Teacher' && auth()->user()->role != 'Accountant')
<link href="{{ asset('admin_assets/css/image-upload-hover.css') }}" rel="stylesheet" type="text/css">
@endif

<body class="bg-gradient-light text-dark">
    <div class="container mb-4">
        <div class="row">
            <!-- profile section -->
            <div class="col-12 col-md-4 mb-3">
                <div class="card o-hidden border-0 shadow-lg">
                    <div class="card-body p-3">
                        <h3 class="mb-0">Student Profile</h3>
                        <hr class="border-secondary">
                        <div class="card text-center mb-3">
                            <div class="py-3 position-relative bg-maroon text-white" style="background-color: #800000;">
                                <img src="{{ asset($students->image ?? 'admin_assets/img/undraw_profile.svg') }}" id="studentImage" 
                                    class="img-fluid mb-2 p-2 profile-image rounded-circle" 
                                    style="width: 200px; height: 200px; object-fit: cover; border-radius: 50%;" alt="Profile Image">
                                @if(auth()->user()->role != 'Teacher' && auth()->user()->role != 'Accountant')
                                <p style="font-size: 10px;">Click the image to change profile</p>
                                <input type="file" id="imageUpload" class="btn btn-primary btn-sm w-100" accept="image/*" hidden>
                                @endif
                                <p class="h5 font-weight-bold mb-1">{{ $students->studentFirst_name }} {{ $students->studentMiddle_name }} {{ $students->studentLast_name }} {{ $students->studentName_ext }}</p>
                                <small class="d-block">Section: {{ $students->studentSection }}</small><br>
                                <small class="d-block">LRN: {{ $students->studentLRN }}</small>
                            </div>
                        </div>
                        <!-- cards for info -->
                        <div class="d-grid gap-3">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title text-primary">Tuition Fee Information</h6>
                                    <p class="mb-2">
                                        <strong>Original Tuition Amount: </strong>{{ number_format($students->studentTuition_amount, 2) }}
                                    </p>
                                    <p class="mb-2">
                                        <strong>Discount Amount: </strong>{{ number_format($students->discountedTuition_amount, 2) }}
                                    </p>
                                    <p class="mb-2">
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
                                            {{ number_format($displayBalance, 2, '.', ',') }}
                                        @endif
                                    </p>
                                    <p class="card-text" style="font-size: 14px;">
                                        <i class="fas fa-tags" style="font-size: 12px;"></i> 
                                        <strong>Discount Type: </strong>{{ $students->studentTuition_discount }}
                                    </p>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title text-primary">Personal Information</h6>
                                    <p class="mb-2"><i class="fas fa-venus-mars" style="font-size: 14px;"></i> <strong>Gender:</strong> {{ $students->studentGender }}</p>
                                    <p class="mb-2"><i class="fas fa-birthday-cake" style="font-size: 14px;"></i> <strong> Birthdate:</strong> {{ $students->studentBirthdate }}</p>
                                    <p class="mb-2"><i class="fas fa-sort-numeric-down-alt" style="font-size: 14px;"></i> <strong> Birthorder:</strong> {{ $students->studentBirthorder }}</p>
                                    <p class="mb-2"><i class="fas fa-map-marker-alt" style="font-size: 14px;"></i> <strong> Address:</strong> {{ $students->studentAddress }}</p>
                                    <p class="mb-2"><i class="fas fa-palette" style="font-size: 14px;"></i> <strong>Hobbies:</strong>  {{ $students->studentHobby }}</p>
                                    <p class="mb-2"><i class="fas fa-thumbs-up" style="font-size: 14px;"></i> <strong>Favorites:</strong>  {{ $students->studentFavorite }}</p>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title text-primary">Parent/Guardian Information</h6>
                                    @forelse($parents as $parent)
                                        <p class="mb-2">
                                            <i class="fas fa-user-tie mr-2"></i>
                                            <strong>{{ $parent->parentRelationship_to_student }}: {{ $parent->parentFirst_name }} {{ $parent->parentLast_name }}</strong> 
                                        </p>
                                        <p class="mb-2">
                                            <i class="fas fa-phone mr-2"></i> {{ $parent->parentMobile_number }}
                                        </p>
                                        <p class="mb-2">
                                            <i class="fas fa-envelope mr-2"></i> {{ $parent->parentEmail }}
                                        </p>
                                        <p class="mb-2">
                                            <i class="fas fa-map-marker-alt mr-2"></i> {{ $parent->parentAddress }}
                                        </p>
                                    @empty
                                        <p class="text-muted">No parent/guardian information available.</p>
                                    @endforelse
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title text-primary">Siblings Connection</h6>
                                    @if($siblings->count() > 0)
                                        <ul class="list-group list-group-flush">
                                            @foreach($siblings as $sibling)
                                                <li class="list-group-item">
                                                    <strong>{{ $sibling->studentFirst_name }} {{ $sibling->studentLast_name }}</strong><br>
                                                    <small>Section: {{ $sibling->studentSection }}</small><br>
                                                    <small>LRN: {{ $sibling->studentLRN }}</small><br>
                                                    <small>Gender: {{ $sibling->studentGender }}</small>
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
            </div>
            <!-- hero Image Section -->
            <div class="col-12 col-md-8">
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
                            <table id="manageStudentShow" class="table table-striped table-hover" style="font-size: 12px;">
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
                                                    Academic Advancement
                                                @else
                                                    Payment
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('admin_assets/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#manageStudentShow').DataTable({
                order: [],
                language: {
                    emptyTable: "This student has no records yet."
                }
            });
        });
    </script>
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
    <!-- <script>
        $(document).ready(function () {
            $('#myTable tbody tr').on('click', function () {
                $('#section-modal').modal('show');
            });
        });
    </script> -->
    

</body>
</x-app>