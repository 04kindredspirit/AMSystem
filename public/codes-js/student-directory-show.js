document.getElementById("studentImage").addEventListener("click", function () {
    document.getElementById("imageUpload").click();
});

document.getElementById("imageUpload").addEventListener("change", function (e) {
    var formData = new FormData();
    formData.append("image", e.target.files[0]);
    formData.append("student_id", "{{ $students->id }}");

    fetch("/upload-student-image", {
        method: "POST",
        body: formData,
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                document.getElementById("studentImage").src = data.imageUrl;
            } else {
                alert("Image upload failed!");
            }
        })
        .catch((error) => {
            console.error("Error:", error);
        });
});
