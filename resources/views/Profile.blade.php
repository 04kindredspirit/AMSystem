<x-app>
@include('Components.navbar')
@section('title', 'Profile')
<link rel="stylesheet" href="{{ asset('admin_assets/vendor/font-awesome-free/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin_assets/css/profilecss.css') }}">
<link href="{{ asset('admin_assets/vendor/font-awesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
<body class="bg-gradient-light text-dark" style="margin: 0;">
    <div class="container mb-4">
        <div class="card o-hidden border-0 shadow-lg">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="mb-0">Profile</h3>
                </div>
                <hr class="border-secondary">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active bg-primary text-white" id="nav-personal-tab" data-bs-toggle="tab" 
                        data-bs-target="#nav-personal" type="button" role="tab" aria-control="nav-personal"
                        aria-selected="true">Personal Details</button>

                        <button class="nav-link bg-danger text-white" id="nav-picture-tab" data-bs-toggle="tab" 
                        data-bs-target="#nav-picture" type="button" role="tab" aria-control="nav-picture"
                        aria-selected="false">Profile Picture</button>

						<button class="nav-link bg-warning text-white" id="nav-password-tab" data-bs-toggle="tab" 
                        data-bs-target="#nav-password" type="button" role="tab" aria-control="nav-password"
                        aria-selected="false">Change Password</button>
                    </div>
                </nav>
				<!-- personal details -->
                <div class="tab-content border border-bottom-0 border-secondary" id="nav-tabContent">
                    <div class="tab-pane fade show active p-3" id="nav-personal" role="tabpanel"
                        aria-labelledby="nav-personal-tab">                       
						<form method="POST" action="{{ route('updatePersonal', $user->id) }}">
							@csrf
							@method ('PUT')
							<div class="tab-pane fade show active p-3" id="nav-personal" role="tabpanel"
								aria-labelledby="nav-personal-tab">
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
								<h2>Personal Details</h2>
								<div class="form-group row">
									<div class="col-12 col-sm-6 col-md-6">
										<div class="form-group">
											<label>First Name</label>
											<input type="text" class="form-control form-control-user rounded" id="firstName" name="first_name" value="{{ auth()->user()->first_name }}">
										</div>
									</div>
									<div class="col-12 col-sm-6 col-md-6">
										<div class="form-group">
											<label>Last Name</label>
											<input type="text" class="form-control form-control-user rounded" id="middleName" name="last_name" value="{{ auth()->user()->last_name }}">
										</div>
									</div>
								</div>
								<div class="form-group row">
									<div class="col-12 col-sm-6 col-md-6">
										<div class="form-group">
											<label>Email</label>
											<input type="email" class="form-control form-control-user rounded" id="email" name="email" value="{{ auth()->user()->email }}">
										</div>
									</div>
									<div class="col-12 col-sm-6 col-md-6">
										<div class="form-group">
											<label>Mobile Number</label>
											<input type="number" class="form-control form-control-user rounded" id="mobileNumber" name="phone_number" value="{{ auth()->user()->phone_number }}">
										</div>
									</div>
								</div>
								<div class="modal-footer d-flex justify-content-center">
									<button class="btn btn-success btn-md">Update Personal Details</button>
								</div>
							</div>
						</form>
                    </div>
                </div>

				<!-- profile picture -->
				<div class="tab-content border border-top-0 border-bottom-0 border-secondary" id="nav-tabContent">
                    <div class="tab-pane fade show p-3" id="nav-picture" role="tabpanel"
                        aria-labelledby="nav-picture-tab">                       
						<div class="tab-pane fade show active p-3 mb-3" id="nav-picture" role="tabpanel"
							aria-labelledby="nav-picture-tab">
							<h2>Update Profile Picture</h2>
							<h4 class="mt-4 text-center">Upload a new profile photo</h4>
							<div class="row g-0 justify-content-center">
								<div class="col-md-3 rounded d-flex flex-column align-items-center justify-content-center">
									<img src="{{ asset($user->image ?? 'admin_assets/img/undraw_profile.svg') }}" id="userImage" class="img-fluid mb-2 p-3" style="width:200px; height:200px; object-fit:cover; border-radius: 50%; " alt="Profile Image">				
									<input type="file" id="imageUpload" class="btn btn-primary w-75 w-md-100 btn-sm" accept="image/*">
								</div>
							</div>
						</div>
                    </div>
                </div>

				<!-- Confirmation Modal -->
				<div class="modal fade" id="confirmPasswordUpdateModal" tabindex="-1" aria-labelledby="confirmPasswordUpdateModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="confirmPasswordUpdateModalLabel">Confirm Password Update</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								Are you sure you want to update your password?
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
								<button type="button" id="confirmUpdateButton" class="btn btn-success">Confirm</button>
							</div>
						</div>
					</div>
				</div>
				
				<!-- password -->
				<div class="tab-content border border-top-0 border-secondary" id="nav-tabContent">
					<div class="tab-pane fade show p-3" id="nav-password" role="tabpanel" aria-labelledby="nav-password-tab">
						<form method="POST" action="{{ route('updatePassword') }}" id="passwordUpdateForm">
							@csrf
							@method('PUT')
							<div class="tab-pane fade show active p-3" id="nav-password" role="tabpanel" aria-labelledby="nav-password-tab">
								<h2>Change Password</h2>
								
								<!-- Display validation errors -->
								@if ($errors->any())
									<div class="alert alert-danger">
										<ul>
											@foreach ($errors->all() as $error)
												<li>{{ $error }}</li>
											@endforeach
										</ul>
									</div>
								@endif

								<!-- Old Password -->
								<div class="form-group">
									<label>Old Password</label>
									<div class="input-group">
										<input type="password" class="form-control form-control-user rounded-start" name="oldPassword" id="oldPassword" required>
										<div class="input-group-append">
											<span class="input-group-text" onclick="togglePasswordVisibility('oldPassword')">
												<i class="fas fa-eye"></i>
											</span>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label>New Password</label>
									<div class="input-group">
										<input type="password" class="form-control form-control-user rounded-start" name="newPassword" id="newPassword" required>
										<div class="input-group-append">
											<span class="input-group-text" onclick="togglePasswordVisibility('newPassword')">
												<i class="fas fa-eye"></i>
											</span>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label>Confirm Password</label>
									<div class="input-group">
										<input type="password" class="form-control form-control-user rounded-start" name="newPassword_confirmation" id="confirmPassword" required>
										<div class="input-group-append">
											<span class="input-group-text" onclick="togglePasswordVisibility('confirmPassword')">
												<i class="fas fa-eye"></i>
											</span>
										</div>
									</div>
								</div>

								<div class="modal-footer d-flex justify-content-center">
									<button type="button" class="btn btn-success btn-md" data-bs-toggle="modal" data-bs-target="#confirmPasswordUpdateModal">
										Update Password
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
            </div>
        </div>
    </div>
    <script src="{{ asset('admin_assets/js/bootstrap.bundle.min.js') }}"></script>
	<script>
		function togglePasswordVisibility(inputId) {
			const passwordInput = document.getElementById(inputId);
			const eyeIcon = passwordInput.nextElementSibling.querySelector("i");

			if (passwordInput.type === "password") {
				passwordInput.type = "text";
				eyeIcon.classList.remove("fa-eye");
				eyeIcon.classList.add("fa-eye-slash");
			} else {
				passwordInput.type = "password";
				eyeIcon.classList.remove("fa-eye-slash");
				eyeIcon.classList.add("fa-eye");
			}
		}

		const form = document.getElementById("passwordUpdateForm");
		const confirmButton = document.getElementById("confirmUpdateButton");

		confirmButton.addEventListener("click", function () {
			form.submit();
		});
	</script>

	<script>
		document.getElementById("imageUpload").addEventListener("change", function (e) {
			var formData = new FormData();
			formData.append("image", e.target.files[0]);
			formData.append("user_id", "{{ $user->id }}");

			fetch("/upload-user-image", {
				method: "POST",
				body: formData,
				headers: {
					"X-CSRF-TOKEN": "{{ csrf_token() }}",
				},
			})
				.then((response) => response.json())
				.then((data) => {
					if (data.success) {
						document.getElementById("userImage").src = data.imageUrl;
					} else {
						alert("Image upload failed!");
					}
				})
				.catch((error) => {
					console.error("Error:", error);
				});
		});
	</script>
	
</body>
</x-app>