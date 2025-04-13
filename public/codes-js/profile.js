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
