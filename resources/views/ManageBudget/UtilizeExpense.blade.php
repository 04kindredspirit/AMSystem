<x-app>
@include('Components.navbar')
@section('title', 'Utilize Expense')
<link rel="stylesheet" href="{{ asset('admin_assets/vendor/font-awesome-free/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin_assets/css/profilecss.css') }}">
<link href="{{ asset('admin_assets/vendor/font-awesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
<body class="bg-gradient-light text-dark" style="margin: 0;">
    <div class="container mb-4">
        <div class="card o-hidden border-0 shadow-lg">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="mb-0">Utilize Expense</h3>
                </div>
                <hr class="border-secondary">
                <div class="card border border-top-0 border-danger">
                    <div class="card-header bg-danger text-white text-center">
                        Control Expenses with Budgeting
                    </div>
                    <div class="card-body">
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
                        <div class="form-group text-center">
                            <label for="balance">Budget</label>
                            <input type="text" class="form-control form-control-user rounded text-center" name="balance" readonly>
                        </div>
                        <form action="{{ route('ManageBudget.UtilizeExpense.store') }}" method="POST">
                            @csrf
                            <div class="row d-flex justify-content-between">
                                <div class="col-12 col-sm-6 col-md-4">
                                    <div class="form-group text-center">
                                        <label for="expense_type_id">Type of Expense</label>
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
                                <div class="col-12 col-sm-6 col-md-4">
                                    <div class="form-group text-center">
                                        <label for="date">Date</label>
                                        <input type="date" class="form-control form-control-user rounded text-center" name="date" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <label for="amount">Amount</label>
                                <input type="text" class="form-control form-control-user rounded text-center" name="amount" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" style="resize: none;" id="description" name="description" rows="3" disable></textarea>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-success btn-md btn-block">Utilize Expense</button>
                            </div>
                        </form>
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