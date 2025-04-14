<x-app>
@include('Components.navbar')
@section('title', 'Payment Records')
<link href="{{ asset('admin_assets/vendor/font-awesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="{{ asset('dataTable/css/dataTables.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('dataTable/css/dataTables.dateTime.min.css') }}">
<link rel="stylesheet" href="{{ asset('dataTable/css/searchBuilder.dataTables.css') }}">
<link rel="stylesheet" href="{{ asset('dataTable/css/buttons.dataTables.css') }}">

<script defer src="{{ asset('dataTable/js/jquery-3.7.1.js') }}"></script>
<script defer src="{{ asset('dataTable/js/bootstrap.bundle.min.js') }}"></script>
<script defer src="{{ asset('dataTable/js/dataTables.js') }}"></script>
<script defer src="{{ asset('dataTable/js/dataTables.bootstrap5.js') }}"></script>
<script defer src="{{ asset('dataTable/js/dataTables.searchBuilder.js') }}"></script>
<script defer src="{{ asset('dataTable/js/searchBuilder.dataTables.js') }}"></script>
<script defer src="{{ asset('dataTable/js/dataTables.dateTime.min.js') }}"></script>
<script defer src="{{ asset('dataTable/js/dataTables.button.js') }}"></script>
<script defer src="{{ asset('dataTable/js/buttons.DataTables.js') }}"></script>
<script defer src="{{ asset('dataTable/js/buttons.print.min.js') }}"></script>
<script defer src="{{ asset('dataTable/js/script.js') }}"></script>

<body class="bg-gradient-light text-dark">
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="mb-0">Payment Records</h3>
                    <a href="{{ route('Payment') }}" class="close" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <hr class="border-secondary" style="margin-bottom: -8px;">
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
                    <table id="paymentTable" class="table table-striped table-hover" style="width:100%">
                        <thead class="table-primary"  style="font-size: 12px">
                            <tr>
                                <th class="text-center">Date</th>
                                <th class="text-center">Payment OR</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Balance</th>
                                <th class="text-center">Tuition Amount</th>
                                <th class="text-center">Amount Paid</th>
                                <th class="text-center">Payment Period</th>
                                <th class="text-center">Type</th>
                                <th class="text-center">Transact By</th>

                            </tr>
                        </thead>
                        <tbody style="font-size: 12px;">
                            @if($payment->count() > 0)
                                @foreach($payment as $pymnt)
                                <tr>
                                    <td class="text-center">{{ $pymnt->paymentDate ?? '' }}</td>
                                    <td class="text-center">{{ $pymnt->paymentOR ?? '' }}</td>
                                    <td class="text-center">{{ $pymnt->studentFullname ?? '' }}</td>
                                    <td class="text-center">{{ number_format($pymnt->balance, 2) ?? '' }}</td>
                                    <td class="text-center">{{ number_format($pymnt->paymentTuitionAmount, 2) ?? '' }}</td>
                                    <td class="text-center">{{ number_format($pymnt->paymentAmount, 2) ?? '' }}</td>
                                    <td class="text-center">{{ $pymnt->paymentPeriod ?? '' }}</td>
                                    <td class="text-center">
                                        @if($pymnt->record_type === 'balance_adjustment')
                                            Balance Adjustment
                                        @else
                                            Payment
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $pymnt->user ? $pymnt->user->first_name . ' ' . $pymnt->user->last_name : 'N/A' }}</td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>   
            </div>
        </div>
    </div>
    <script src="{{ asset('admin_assets/js/bootstrap.bundle.min.js') }}"></script>

</body>
</x-app>