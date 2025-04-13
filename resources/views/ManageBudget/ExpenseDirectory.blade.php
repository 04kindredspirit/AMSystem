<x-app>
@include('Components.navbar')
@section('title', 'Expense Directory')
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

<meta name="csrf-token" content="{{ csrf_token() }}">
<body class="bg-gradient-light text-dark">
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="mb-0">Expense Directory</h3>
                </div>
                <hr class="border-secondary" style="margin-bottom: -8px;">             
                <div class="table-responsive">
                    <table id="expenseTable" class="table table-striped table-hover display" style="width:100%; text-align: left;">
                        <thead class="table-primary">
                            <tr>
                                <th class="text-center" width="15%">Expense Type</th>
                                <th class="text-center" width="40%">Description</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">User</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($expense->count() > 0)
                                @foreach($expense as $exp)
                                    <tr>
                                        <td class="text-center">{{ $exp->expenseType->name ?? 'N/A' }}</td>
                                        <td>{{ $exp->description ?? '' }}</td>
                                        <td class="text-center">{{ number_format($exp->amount, 2) ?? '' }}</td>
                                        <td class="text-center">{{ $exp->date }}</td>
                                        <td class="text-center">{{ $exp->user->first_name }} {{ $exp->user->last_name }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</x-app>