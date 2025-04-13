$(document).ready(function () {
    $("#search_query").on("keyup", function () {
        var searchQuery = $(this).val();

        if (searchQuery.length > 0) {
            $.ajax({
                url: "{{ route('student.search.ajax') }}",
                method: "GET",
                data: { search_query: searchQuery },
                success: function (response) {
                    if (response.error) {
                        $("#noStudentAlert")
                            .removeClass("d-none")
                            .text(response.error);
                    } else if (response) {
                        $("#noStudentAlert").addClass("d-none");

                        $("#paymentFname").val(response.full_name || "");
                        $("#paymentLrn").val(response.studentLRN || "");
                        $("#paymentAmount").val(response.paymentAmount || "");
                        $("#paymentDiscount").val(
                            response.studentSection || ""
                        );
                        $("#tuitionAmount").val(response.balance || "");
                    } else {
                        showNoStudentAlert();
                    }
                },
                error: function (xhr, status, error) {
                    showNoStudentAlert();
                },
            });
        } else {
            $(
                "#paymentFname, #paymentLrn, #paymentAmount, #paymentDiscount, #tuitionAmount"
            ).val("");
            $("#noStudentAlert").addClass("d-none");
        }
    });

    function showNoStudentAlert() {
        $("#noStudentAlert").removeClass("d-none");
    }

    window.closeAlert = function () {
        $("#noStudentAlert").addClass("d-none");
    };
});
