<x-app>
@include('Components.navbar')
@section('title', 'Discounts')
<link href="{{ asset('admin_assets/vendor/font-awesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">

<meta name="csrf-token" content="{{ csrf_token() }}">
<body class="bg-gradient-light text-dark">
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg mb-4">
            <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between">
                    <h3 class="mb-0">List of Discounts</h3>
                </div>
                <hr class="border-secondary">
                @if (session('success'))
                    <div class="alert alert-success" id="alert-sucess">
                        {{ session('success') }}
                        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close" onclick="closeAlert('success-alert')">
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
                <div class="d-flex justify-content-end mb-3">
                    <div class="p-2">
                        @if (auth()->user()->role != 'Teacher')
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#section-modal">+ Add New Section</button>
                        @endif
                    </div>
                </div>
                
                <!-- table -->
                <div class="table-responsive table-sm">
                    <table id="discountsTable" class="table table-striped table-hover" style="width:100%">
                        <thead class="table-primary text-center">
                            <tr>
                                <th>Discount Type</th>
                                <th>Percentage</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @if($discounts->count() > 0)
                                @foreach($discounts as $discount)
                                <tr>
                                    <td>{{ $discount->discount_type }}</td>
                                    <td>{{ $discount->percentage }}%</td>
                                    <td>
                                        <span class="badge badge-{{ $discount->status == 'Active' ? 'success' : 'danger' }}">{{ $discount->status }}</span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-warning edit-btn my-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $discount->id }}" style="font-size: 12px;">
                                        <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <div class="modal fade" id="editModal{{ $discount->id }}" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST" action="{{ route('SystemControls.discounts.update', $discount->id) }}">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="card border border-top-0 border-danger">
                                                                <div class="card-header bg-danger text-white text-center">
                                                                    Edit Section
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="form-group row">
                                                                        <div class="col-12 col-sm-6 col-md-4 text-start">
                                                                            <label for="discountType">Discount Type</label>
                                                                            <input type="text" class="form-control form-control-user rounded" name="discount_type" id="discountType{{ $discount->id }}" value="{{ $discount->discount_type }}" required>
                                                                        </div>
                                                                        <div class="col-12 col-sm-6 col-md-4 text-start">
                                                                            <label for="discountPercent">Percentage</label>
                                                                            <input type="text" class="form-control form-control-user rounded" name="percentage" id="discountPercent{{ $discount->id }}" value="{{ $discount->percentage }}" required>
                                                                        </div>
                                                                        <div class="col-12 col-sm-6 col-md-4 text-start">
                                                                            <label for="status">Status</label>
                                                                            <select class="form-control" name="status" id="status{{ $discount->status }}" required>
                                                                                <option disabled {{ is_null($discount->status) ? 'selected' : '' }}>- Select Status -</option>
                                                                                <option value="Active" {{ $discount->status == 'Active' ? 'selected' : '' }}>Active</option>
                                                                                <option value="Inactive" {{ $discount->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer d-flex justify-content-center">
                                                                        <button type="button" data-bs-dismiss="modal" class="btn btn-danger">Close</button>
                                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                                    </div> 
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- discount modal -->
                <div class="modal fade" id="section-modal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('SystemControls.discounts.store') }}">
                                    @csrf
                                    <div class="card border border-top-0 border-danger">
                                        <div class="card-header bg-danger text-white text-center">
                                            Add New Type of Discount
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group row mb-3">
                                                <div class="col-12 col-sm-6 col-md-4 text-start">
                                                    <label>Discount Type</label>
                                                    <input type="text" class="form-control" name="discount_type" required>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-4 text-start">
                                                    <label>Percentage</label>
                                                    <input type="number" step="0.01" class="form-control" name="percentage" required>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-4 text-start">
                                                    <label>Status</label>
                                                    <select class="form-control" name="status" required>
                                                        <option value="Active">Active</option>
                                                        <option value="Inactive">Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer d-flex justify-content-center">
                                                <button type="button" data-bs-dismiss="modal" class="btn btn-danger">Close</button>
                                                <button type="submit" class="btn btn-primary">Save Discount</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    

    <script src="{{ asset('admin_assets/js/bootstrap.bundle.min.js') }}"></script>

    <!-- <script>
    $(document).ready(function() {
        $('.edit-discount').click(function() {
            var discountId = $(this).data('id');
            var discountType = $(this).data('type');
            var percentage = $(this).data('percent');
            var status = $(this).data('status');
            
            $('#editDiscountType').val(discountType);
            $('#editPercentage').val(percentage);
            $('#editStatus').val(status);
            
            $('#editDiscountForm').attr('action', '/SystemControls/discounts/' + discountId + '/update');
            
            $('#editDiscountModal').modal('show');
        });
    });
    </script> -->
</x-app>