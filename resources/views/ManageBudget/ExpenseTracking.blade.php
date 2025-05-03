<x-app>
@include('Components.navbar')
@section('title', 'Expense Tracking')
<link rel="stylesheet" href="{{ asset('admin_assets/vendor/font-awesome-free/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin_assets/css/profilecss.css') }}">
<link href="{{ asset('admin_assets/vendor/font-awesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
<body class="bg-gradient-light text-dark" style="margin: 0;">
    <div class="container mb-4">
        <div class="card o-hidden border-0 shadow-lg">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="mb-0">Expense Charts</h3>
                </div>
                <hr class="border-secondary">
                <div class="card-body">
                    <div class="row">
                        <!-- utilities -->
                        <div class="col-12 col-sm-6 col-md-4 mb-4">
                            <div class="card shadow-sm">
                                <div class="card-header text-white">
                                    <h5 class="card-title mb-0">Utilities</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="pieUtilities" style="width:100%; max-width:1000px"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- salaries -->
                        <div class="col-12 col-sm-6 col-md-4 mb-4">
                            <div class="card shadow-sm">
                                <div class="card-header text-white">
                                    <h5 class="card-title mb-0">Salaries</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="pieSalaries" style="width:100%; max-width:1000px"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- petty cash -->
                        <div class="col-12 col-sm-6 col-md-4 mb-4">
                            <div class="card shadow-sm">
                                <div class="card-header text-white">
                                    <h5 class="card-title mb-0">Petty Cash</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="piePettyCash" style="width:100%; max-width:1000px"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <!-- maintenance -->
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="card shadow-sm">
                                <div class="card-header text-white">
                                    <h5 class="card-title mb-0">Maintenance</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="pieMaintenance" style="width:100%; max-width:1000px"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- supplies -->
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="card shadow-sm">
                                <div class="card-header text-white">
                                    <h5 class="card-title mb-0">Supplies</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="pieSupplies" style="width:100%; max-width:1000px"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('admin_assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin_assets/vendor/chart.js/Chart.min.js') }}"></script>
    <script>
        const utilitiesData = @json($utilities['data']);
        const utilitiesBalance = @json($utilities['remainingBalance']);
        const utilitiesTotalAllocated = @json($utilities['totalAllocated']);
        
        const salariesData = @json($salaries['data']);
        const salariesBalance = @json($salaries['remainingBalance']);
        const salariesTotalAllocated = @json($salaries['totalAllocated']);
        
        const pettyCashData = @json($pettyCash['data']);
        const pettyCashBalance = @json($pettyCash['remainingBalance']);
        const pettyCashTotalAllocated = @json($pettyCash['totalAllocated']);
        
        const maintenanceData = @json($maintenance['data']);
        const maintenanceBalance = @json($maintenance['remainingBalance']);
        const maintenanceTotalAllocated = @json($maintenance['totalAllocated']);
        
        const suppliesData = @json($supplies['data']);
        const suppliesBalance = @json($supplies['remainingBalance']);
        const suppliesTotalAllocated = @json($supplies['totalAllocated']);
    </script>
    <script src="{{ asset('codes-js/expense-tracking.js') }}"></script>
</body>
</x-app>