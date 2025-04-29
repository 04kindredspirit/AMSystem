<x-app>
@include('Components.navbar')
@section('title', 'Parent Directory')
<link href="{{ asset('admin_assets/vendor/font-awesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
<body class="bg-gradient-light text-dark">
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="mb-0">Parent Profile</h3>
                </div>
                <hr class="border-secondary">
                <div class="card mb-3" style="max-width: 100%;">
                    <div class="row g-0">
                        <!-- left column, image and upload -->
                        <div class="col-md-3 bg-secondary bg-danger rounded-left d-flex flex-column align-items-center justify-content-center py-3">
                        <img src="{{ asset($parent->image ?? 'admin_assets/img/undraw_profile.svg') }}" id="parentImage" class="img-fluid mb-2 p-3" style="width:200px; height:200px; object-fit:cover; border-radius: 50%; " alt="Profile Image">
                        @if(auth()->user()->role != 'Teacher' && auth()->user()->role != 'Accountant')
                            <input type="file" id="imageUpload" class="btn btn-primary w-75 w-md-100 btn-sm" accept="image/*">
                        @endif
                        </div>
                        <!-- right column, information -->
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">Personal Information</h5>
                                <p class="card-text h5 text-danger font-weight-bold">
                                    {{ $parent->parentFirst_name }} {{ $parent->parentMiddle_name }} {{ $parent->parentLast_name }}
                                </p>
                                <span class="card-text badge badge-warning mb-3 text-uppercase p-1 text-dark">
                                    {{ $parent->parentOccupation }}
                                </span>
                                <p class="card-text">
                                    <i class="fas fa-link"></i> 
                                    <strong>Link to enrolled student(s):</strong>
                                    @if ($parent->students->isNotEmpty())
                                        @foreach ($parent->students as $index => $student)
                                            {{ $student->studentFirst_name }} {{ $student->studentLast_name }}
                                            @if (!$loop->last)
                                                , 
                                            @endif
                                        @endforeach
                                    @else
                                        No linked students.
                                    @endif
                                </p>
                                <p class="card-text"><i class="fas fa-birthday-cake"></i> <strong>Birthdate:</strong> {{ $parent->parentBirthdate }}</p>
                                <p class="card-text"><i class="fas fa-graduation-cap"></i> <strong>Educational Attainment:</strong> {{ $parent->parentEducational_attainment }}</p>
                                <p class="card-text"><i class="fas fa-mobile"></i> <strong>Mobile Number:</strong> {{ $parent->parentMobile_number }}</p>
                                <p class="card-text"><i class="fas fa-envelope"></i> <strong>Email Address:</strong> {{ $parent->parentEmail }}</p>
                                <p class="card-text"><i class="fas fa-map-marker-alt"></i> <strong>Address:</strong> {{ $parent->parentAddress }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('admin_assets/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        document.getElementById('imageUpload').addEventListener('change', function(e) {
            var formData = new FormData();
            formData.append('image', e.target.files[0]);
            formData.append('parent_id', '{{ $parent->id }}');

            fetch('/upload-image', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('parentImage').src = data.imageUrl;
                } else {
                    alert('Image upload failed!');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    </script>

</body>
</x-app>