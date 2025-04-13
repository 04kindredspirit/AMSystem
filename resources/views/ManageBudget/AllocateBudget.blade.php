<x-app>
@include('Components.navbar')
@section('title', 'Allocate Budget')
<link rel="stylesheet" href="{{ asset('admin_assets/vendor/font-awesome-free/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin_assets/css/profilecss.css') }}">
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
<body class="bg-gradient-light text-dark" style="margin: 0;">
    <div class="container mb-4">
        <div class="card o-hidden border-0 shadow-lg">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="mb-0">Allocate Budget / Replenish Expense</h3>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#reg-modal">View Records</button>
                </div>
                <hr class="border-secondary">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active bg-primary text-white" id="nav-allocate-tab" data-bs-toggle="tab" 
                        data-bs-target="#nav-allocate" type="button" role="tab" aria-control="nav-allocate"
                        aria-selected="true">Allocate Budget</button>

                        <button class="nav-link bg-danger text-white" id="nav-replenish-tab" data-bs-toggle="tab" 
                        data-bs-target="#nav-replenish" type="button" role="tab" aria-control="nav-replenish"
                        aria-selected="false">Replenish Expense</button>
                    </div>
                </nav>
                <!-- allocate budget -->
                <div class="tab-content border border-bottom-0 border-secondary rounded" id="nav-tabContent">
                    <div class="tab-pane fade show active p-3" id="nav-allocate" role="tabpanel"
                        aria-labelledby="nav-allocate-tab">                       
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
                        @php
                            $totalAllocations = App\Models\Allocation::sum('amount');
                            $totalReplenishments = App\Models\CategoryAllocation::sum('amount');
                            $overallBalance = $totalAllocations - $totalReplenishments;
                        @endphp
                        <h2>Allocate Budget</h2>
                        <form action="{{ route('ManageBudget.AllocateBudget.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-sm-6 col-md-4">
                                    <div class="form-group text-center">
                                        <label>Date</label>
                                        <input type="date" class="form-control form-control-user rounded text-center" id="date" name="date" required>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-8 text-center">
                                    <label>Overall Remaining Budget</label>
                                    <input type="text" class="form-control form-control-user font-weight-bold rounded text-center" id="" name="remainingBudget" value="₱ {{ number_format($overallBalance, 2) }}" readonly>
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <label>Amount</label>
                                <input type="text" class="form-control form-control-user rounded text-center" id="amount" name="amount">
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-success btn-md btn-block">Allocate / Replenish</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- replenish expense -->
                <div class="tab-content border border-top-0 border-secondary rounded" id="nav-tabContent">
                    <div class="tab-pane fade show p-3" id="nav-replenish" role="tabpanel"
                        aria-labelledby="nav-replenish-tab">                       
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
                        <div>
                            <h5 class="font-weight-bold my-3"></h5>
                        </div>
                        <h2>Replenish Expense</h2>
                        <p>Remaining Budget ₱ {{ number_format($overallBalance, 2) }}</p>
                        <form action="{{ route('ManageBudget.ReplenishExpense.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-sm-6 col-md-4">
                                    <div class="form-group text-center">
                                        <label>Type of Expense</label>
                                        <select class="form-control text-center" name="expense_type_id" id="expenseTypeSelect" required>
                                            <option value="" disabled selected>- Select Expenses -</option>
                                            @foreach($expenseTypes as $type)
                                                <option value="{{ $type->id }}" data-balance="{{ $type->balance }}">
                                                    {{ $type->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>                                    
                                </div>
                                <div class="col-12 col-sm-6 col-md-8">
                                    <div class="form-group text-center">
                                        <label>Budget</label>
                                        <input type="text" class="form-control form-control-user rounded text-center" name="balance" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-6 col-md-4">
                                    <div class="form-group text-center">
                                        <label>Date</label>
                                        <input type="date" class="form-control form-control-user rounded text-center" name="date" required>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-8">
                                    <div class="form-group text-center">
                                        <label>Amount</label>
                                        <input type="text" class="form-control form-control-user rounded text-center" name="amount" required>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-success btn-md btn-block">Replenish Expense</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- view records -->
                <div class="modal fade" id="reg-modal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modal-title">Allocate/Replenishment Records</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <nav>
                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                        <button class="nav-link active bg-primary text-white" id="nav-allocate-record-tab" data-bs-toggle="tab" 
                                        data-bs-target="#nav-allocate-record" type="button" role="tab" aria-control="nav-allocate-record"
                                        aria-selected="true">Allocate Budget</button>

                                        <button class="nav-link bg-danger text-white" id="nav-replenish-record-tab" data-bs-toggle="tab" 
                                        data-bs-target="#nav-replenish-record" type="button" role="tab" aria-control="nav-replenish-record"
                                        aria-selected="false">Replenish Expense</button>
                                    </div>
                                </nav>  
                                <div class="tab-content border border-bottom-0 border-secondary rounded" id="nav-tabContent">
                                    <div class="tab-pane fade show active p-3" id="nav-allocate-record" role="tabpanel"
                                        aria-labelledby="nav-allocate-record-tab">                       
                                        <h4>Allocate Budget Records</h4>
                                        <hr class="border-secondary" style="margin-bottom: -8px;">  
                                        <div class="table-responsive table-sm">
                                            <table id="allocateTable" class="table table-striped table-hover display">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th class="text-center">Date</th>
                                                        <th class="text-center">Amount</th>
                                                        <th class="text-center">User</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($allocateRecords->count() > 0)
                                                        @foreach($allocateRecords as $aR)
                                                            <tr>
                                                                <td class="text-center">{{ $aR->date }}</td>
                                                                <td class="text-center">{{ number_format($aR->amount, 2) ?? '' }}</td>
                                                                <td class="text-center">{{ $aR->user->first_name }} {{ $aR->user->last_name }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-content border border-top-0 border-secondary rounded" id="nav-tabContent">
                                    <div class="tab-pane fade show p-3" id="nav-replenish-record" role="tabpanel"
                                        aria-labelledby="nav-replenish-record">                       
                                        <h4>Replenish Expense Records</h4>
                                        <hr class="border-secondary" style="margin-bottom: -8px;">
                                        <div class="table-responsive table-sm">
                                            <table id="replenishTable" class="table table-striped table-hover display">
                                                <thead class="table-primary">
                                                    <th class="text-center">Date</th>
                                                    <th class="text-center">Amount</th>
                                                    <th class="text-center">Expense Type</th>
                                                    <th class="text-center">User</th>
                                                </thead>
                                                <tbody>
                                                    @if ($expenseRecords->count() > 0)
                                                        @foreach($expenseRecords as $eR)
                                                            <tr>
                                                                <td class="text-center">{{ $eR->date }}</td>
                                                                <td class="text-center">{{ number_format($eR->amount, 2) ?? '' }}</td>
                                                                <td class="text-center">{{ $eR->expenseType->name ?? 'N/A' }}</td>
                                                                <td class="text-center">{{ $eR->user->first_name }} {{ $eR->user->last_name }}</td>
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
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('admin_assets/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        document.getElementById('expenseTypeSelect').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const balance = selectedOption.getAttribute('data-balance');
            const formattedBalance = new Intl.NumberFormat('en-US', {
                style: 'decimal',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(balance);

            document.querySelector('input[name="balance"]').value = formattedBalance;
        });
    </script>
</body>
</x-app>