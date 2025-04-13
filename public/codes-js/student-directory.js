document.addEventListener("DOMContentLoaded", function () {
    const dropdown = document.getElementById("linkToStudent");
    if (dropdown) {
        dropdown.addEventListener("change", function () {
            console.log("Dropdown changed!");

            var selectedStudentId = this.value;
            fetch("/update-linked-students", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
                body: JSON.stringify({ student_id: selectedStudentId }),
            })
                .then((response) => response.json())
                .then((data) => {
                    dropdown.innerHTML = "<option>- Select Student -</option>";

                    data.linkedStudents.forEach((student) => {
                        const option = document.createElement("option");
                        option.value = student.id;
                        option.text = `${student.studentFirst_name} ${student.studentLast_name}`;
                        dropdown.appendChild(option);
                    });
                })
                .catch((error) => console.error("Error:", error));
        });
    } else {
        console.error("Dropdown element not found!");
    }
});
