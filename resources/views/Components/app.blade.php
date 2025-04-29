<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>AMSystem - @yield('title')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link href="{{ asset('admin_assets/vendor/font-awesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin_assets/bootstrap-5.3.3-dist/css/bootstrap.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="{{ asset('admin_assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #800000;">
            @php
                $role = auth()->user()->role;
                $dashboardRoute = match ($role) {
                    'SuperAdmin' => 'superadmin.dashboard',
                    'Accountant' => 'accountant.dashboard',
                    'Teacher' => 'teacher.dashboard',
                    default => '#',
                };
            @endphp
            
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route($dashboardRoute) }}">
                <div class="sidebar-brand-text mx-3">AMSystem</div>
            </a>
            <hr class="sidebar-divider my-0" style="border-color: white;">

            <li class="nav-item">
                <x-sidebar href="{{ route($dashboardRoute) }}" :active="request()->routeIs($dashboardRoute)">
                    <i class="fas fa-info-circle text-primary"></i>
                    <span>Dashboard</span>
                </x-sidebar>
            </li>

            <hr class="sidebar-divider" style="border-color: white;">

            <!-- Show Manage Students for teacher and other users -->
            <li class="nav-item">
                <a class="nav-link @if(request()->routeIs('ManageStudent/addstudent') || request()->routeIs('ManageStudent/StudentDirectory')) collapsed show @else collapsed @endif" 
                href="#" 
                data-toggle="collapse" 
                data-target="#collapseTwo"
                aria-expanded="{{ request()->routeIs('ManageStudent/addstudent') || request()->routeIs('ManageStudent/StudentDirectory') ? 'true' : 'false' }}"
                aria-controls="collapseTwo">
                    <i class="fas fa-users text-primary"></i>
                    <span>Manage Students</span>
                </a>
                <div id="collapseTwo" class="collapse @if(request()->routeIs('ManageStudent/addstudent') || request()->routeIs('ManageStudent/StudentDirectory')) show @endif" 
                    aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item @if(request()->routeIs('ManageStudent/addstudent')) bg-gray-200 text-primary font-weight-bold @endif" href="{{ route('ManageStudent/addstudent') }}">
                            <i class="fas fa-user-plus mr-1 text-danger"></i> Add Student
                        </a>
                        <a class="collapse-item @if(request()->routeIs('ManageStudent/StudentDirectory')) bg-gray-200 text-primary font-weight-bold @endif" href="{{ route('ManageStudent/StudentDirectory') }}">
                            <i class="fas fa-address-book mr-1 text-danger"></i> Student Directory
                        </a>
                    </div>
                </div>
            </li>

            <!-- Payment Records -->
            @if(auth()->user()->role != 'Teacher')
            <li class="nav-item">
                <a class="nav-link @if(request()->routeIs('Payment') || request()->routeIs('PaymentRecords')) show @else collapsed @endif"
                href="#"
                data-toggle="collapse"
                data-target="#collapseThree"
                aria-expanded="{{ request()->routeIs('Payment') || request()->routeIs('PaymentRecords') ? 'true' : 'false' }}"
                aria-controls="collapseThree">
                    <i class="fas fa-hand-holding-usd text-primary"></i>
                    <span>Manage Tuition</span>
                </a>
                <div id="collapseThree" class="collapse @if(request()->routeIs('Payment') || request()->routeIs('PaymentRecords')) show @endif"
                    aria-labelledby="headingThree" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item @if(request()->routeIs('Payment')) bg-gray-200 text-primary font-weight-bold @endif" href="{{ route('Payment') }}">
                            <i class="fas fa-money-bill-wave-alt mr-1 text-danger"></i> Tuition Payment
                        </a>
                        <a class="collapse-item @if(request()->routeIs('PaymentRecords')) bg-gray-200 text-primary font-weight-bold @endif" href="{{ route('PaymentRecords') }}">
                            <i class="fas fa-scroll mr-1 text-danger"></i> Payment Records
                        </a>
                    </div>
                </div>
            </li>
            @endif
            
            <!-- show manage budget and expense tracking for non-teachers -->
            @if(auth()->user()->role != 'Teacher') <!-- hide for teacher -->
            <li class="nav-item">
                <a class="nav-link @if(request()->routeIs('ManageBudget.AllocateBudget') || request()->routeIs('ManageBudget.UtilizeExpense') || request()->routeIs('ManageBudget.ExpenseTracking') || request()->routeIs('ManageBudget.ExpenseDirectory')) show @else collapsed @endif"
                href="#" 
                data-toggle="collapse" 
                data-target="#collpaseItem"
                aria-expanded="{{ request()->routeIs('ManageBudget.AllocateBudget') || request()->routeIs('ManageBudget.UtilizeExpense') || request()->routeIs('ManageBudget.ExpenseTracking') || request()->routeIs('ManageBudget.ExpenseDirectory') ? 'true' : 'false' }}"
                aria-controls="collpaseItem">
                    <i class="fas fa-coins text-primary"></i>
                    <span>Manage Budget</span>
                </a>
                <div id="collpaseItem" class="collapse @if(request()->routeIs('ManageBudget.AllocateBudget') || request()->routeIs('ManageBudget.UtilizeExpense') || request()->routeIs('ManageBudget.ExpenseTracking') || request()->routeIs('ManageBudget.ExpenseDirectory')) show @endif"
                    aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        @if(auth()->user()->role != 'Accountant')
                        <a class="collapse-item @if(request()->routeIs('ManageBudget.AllocateBudget')) bg-gray-200 text-primary font-weight-bold @endif" href="{{ route('ManageBudget.AllocateBudget') }}">
                            <i class="fas fa-dollar-sign mr-1 text-danger"></i> Allocate Budget
                        </a>
                        @endif
                        <a class="collapse-item @if(request()->routeIs('ManageBudget.UtilizeExpense')) bg-gray-200 text-primary font-weight-bold @endif" href="{{ route('ManageBudget.UtilizeExpense') }}">
                            <i class="fas fa-sync-alt mr-1 text-danger"></i> Utilitze Expense
                        </a>
                        <a class="collapse-item @if(request()->routeIs('ManageBudget.ExpenseTracking')) bg-gray-200 text-primary font-weight-bold @endif" href="{{ route('ManageBudget.ExpenseTracking') }}">
                            <i class="fas fa-chart-pie mr-1 text-danger"></i> Expense Chart
                        </a>
                        <a class="collapse-item @if(request()->routeIs('ManageBudget.ExpenseDirectory')) bg-gray-200 text-primary font-weight-bold @endif" href="{{ route('ManageBudget.ExpenseDirectory') }}">
                            <i class="fas fa-folder-open mr-1 text-danger"></i> Expense Directory
                        </a>
                    </div>
                </div>
            </li>
            @endif

            <!-- Show Access Security for non-teachers -->
            @if(auth()->user()->role != 'Teacher' && auth()->user()->role != 'Accountant')
            <li class="nav-item">
                <a class="nav-link @if(request()->routeIs('SystemControls.access-security') || request()->routeIs('SystemControls.AcademicAdvancement') || request()->routeIs('SystemControls.section') || request()->routeIs('SystemControls.schoolyear')) show @else collapsed @endif"
                href="#" 
                data-toggle="collapse" 
                data-target="#collapseFive"
                aria-expanded="{{ request()->routeIs('SystemControls.access-security') || request()->routeIs('SystemControls.AcademicAdvancement') || request()->routeIs('SystemControls.section') || request()->routeIs('SystemControls.schoolyear') ? 'true' : 'false' }}"
                aria-controls="collapseFive">
                    <i class="fas fa-cogs text-primary"></i>
                    <span>System Controls</span>
                </a>
                <div id="collapseFive" class="collapse @if(request()->routeIs('SystemControls.access-security') || request()->routeIs('SystemControls.AcademicAdvancement') || request()->routeIs('SystemControls.section') || request()->routeIs('SystemControls.schoolyear')) show @endif"
                    aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item @if(request()->routeIs('SystemControls.access-security')) bg-gray-200 text-primary font-weight-bold @endif" href="{{ route('SystemControls.access-security') }}">
                            <i class="fas fa-user-shield mr-1 text-danger"></i> Access Security
                        </a>
                        <a class="collapse-item @if(request()->routeIs('SystemControls.AcademicAdvancement')) bg-gray-200 text-primary font-weight-bold @endif" href="{{ route('SystemControls.AcademicAdvancement') }}" style="font-size: 12px;">
                            <i class="fas fa-sort-numeric-up-alt mr-1 text-danger"></i> Academic Advancement
                        </a>
                        <a class="collapse-item @if(request()->routeIs('SystemControls.section')) bg-gray-200 text-primary font-weight-bold @endif" href="{{ route('SystemControls.section') }}">
                            <i class="fas fa-puzzle-piece mr-1 text-danger"></i> Section
                        </a>
                        <a class="collapse-item @if(request()->routeIs('SystemControls.schoolyear')) bg-gray-200 text-primary font-weight-bold @endif" href="{{ route('SystemControls.schoolyear') }}">
                            <i class="fas fa-calendar-alt mr-1 text-danger"></i> School Year
                        </a>
                    </div>
                </div>
            </li>
            @endif

            <hr class="sidebar-divider d-none d-md-block" style="border-color: white;">

            <!-- Always show ParentList -->
            <li class="nav-item">
                <x-sidebar href="{{ route('ParentDirectory') }}" :active="request()->routeIs('ParentDirectory')">
                    <i class="fas fa-address-book text-primary"></i>
                    <span>Parent Directory</span>
                </x-sidebar>
            </li>

            <hr class="sidebar-divider d-none d-md-block" style="border-color: white;">

            <!-- Always show Profile -->
            <li class="nav-item">
                <x-sidebar href="{{ route('Profile') }}" :active="request()->routeIs('Profile')">
                    <i class="fas fa-user-edit text-primary"></i>
                    <span>Profile</span>
                </x-sidebar>
            </li>

            <hr class="sidebar-divider d-none d-md-block" style="border-color: white;">

            <!-- Always show Manuals -->
            <li class="nav-item">
                <x-sidebar href="{{ asset('manuals/AMSystem_UserManual.pdf') }}" :active="request()->routeIs('manuals')" class="download-link">
                    <i class="fas fa-info-circle text-primary"></i>
                    <span>Manuals</span>
                </x-sidebar>
            </li>

            <div class="text-center d-none d-md-inline mt-3">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>


        <!-- This is where the content -->
        <div class="container-fluid" style="padding: 0; margin-0;">
            {{ $slot }}
        </div>

    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
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

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('admin_assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin_assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin_assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/sb-admin-2.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const downloadLinks = document.querySelectorAll('.download-link');

            downloadLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    const fileUrl = link.getAttribute('href');
                    const a = document.createElement('a');
                    a.href = fileUrl;
                    a.download = fileUrl.split('/').pop();
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                });
            });
        });
    </script>

</body>

</html>