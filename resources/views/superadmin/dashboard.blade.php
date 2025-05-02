<x-app>
@include('Components.navbar')
@section('title', 'Superadmin Dashboard')
<style>
    .col-xl-5ths {
        flex: 0 0 20%;
        max-width: 20%;
    }

    @media (min-width: 1200px) {
        .col-xl-5ths {
            flex: 0 0 20%;
            max-width: 20%;
        }
    }

    @media (max-width: 1199.98px) and (min-width: 768px) {
        .col-xl-5ths {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }

    @media (max-width: 767.98px) {
        .col-xl-5ths {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }

    .table-wrapper {
        max-height: 300px;
        overflow-y: auto;
    }

    .table thead th {
        position: sticky;
        top: 0;
        background-color: #cfe2ff; /* Same as table-primary */
        z-index: 1;
    }
</style>
<body class="bg-gradient-light text-dark">
    <div id="wrapper">
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <div class="container-fluid">
                    <!-- page heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>

                    <!-- content row -->
                    <div class="row">
                        <!-- earnings (monthly) -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Earnings (Monthly)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">₱ {{ number_format($monthlyEarnings, 2) }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- earnings (annually) -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Earnings (Annual)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">₱ {{ number_format($annualEarnings, 2) }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- number of students -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Number of Students
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $totalStudents }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-portrait fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- number of active accounts -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Number of Accounts</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAccounts }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        @foreach($expenseBalances as $category => $balance)
                        <div class="col-xl-5ths col-md-6 mb-4">
                            <!-- Fixed the unclosed parenthesis in the border-left class -->
                            <div class="card border-left-{{ $balance['percentage'] < 10 ? 'danger' : ($balance['percentage'] < 30 ? 'warning' : 'success') }} shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                {{ $category }}
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                ₱ {{ number_format($balance['remaining'], 2) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- line area chart -->
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <div class="card shadow mb-4">
                            <div
                                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-area">
                                    <canvas id="myChart" style="width:100%; max-width:1000px"></canvas>
                                </div>
                            </div>
                        </div>
                    </div> 
                    
                    <div class="card o-hidden border-0 shadow-lg mb-4">
                        <div class="card-body p4">
                            <div class="d-flex align-items-center">
                                <h3 class="mb-0">Activity Logs</h3>
                            </div>
                            <hr class="boder-danger">

                            <div class="table-wrapper">
                                <table id="myTable" class="table table-striped table-hover" style="width:100%">
                                    <thead class="table-primary text-center">
                                        <tr>
                                            <th width="20%">User</th>
                                            <th width="60%">Activity</th>
                                            <th width="20%">Time</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        @forelse ($logs as $log)
                                            <tr>
                                                <td>{{ $log->user->first_name. ' ' . $log->user->last_name ?? 'Unknown User' }}</td>
                                                <td>{{ $log->activity }}</td>
                                                <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">No activity logs found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- logout modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Done for now? Click 'Logout' to wrap up.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="{{ route('logout') }}">Logout</a>
                </div>
            </div>
        </div>
    </div>
    @include('Components.footer') 
</body>

<script src="{{ asset('admin_assets/vendor/chart.js/Chart.min.js') }}"></script>
<script>
    const ctx = document.getElementById('myChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [
                {
                    label: 'Monthly Earnings',
                    data: @json($monthlyEarningsData),
                    borderColor: 'blue',
                    backgroundColor: 'rgba(0, 0, 255, 0.2)',
                    yAxisID: 'y',
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    type: 'linear',
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Earnings (₱)'
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });
</script>

</x-app>