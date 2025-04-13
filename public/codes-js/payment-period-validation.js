document.addEventListener("DOMContentLoaded", function () {
    const paymentPeriodSelect = document.querySelector(
        'select[name="paymentPeriod"]'
    );
    const paymentLrnInput = document.getElementById("paymentLrn");
    const form = document.querySelector("form");

    function showError(message) {
        const existingError = document.querySelector(".payment-period-error");
        if (existingError) {
            existingError.remove();
        }

        const errorDiv = document.createElement("div");
        errorDiv.className =
            "payment-period-error alert-danger border border-danger rounded text-center mt-2 p-2";
        errorDiv.textContent = message;

        paymentPeriodSelect.insertAdjacentElement("afterend", errorDiv);
    }

    function clearError() {
        const existingError = document.querySelector(".payment-period-error");
        if (existingError) {
            existingError.remove();
        }
    }

    paymentPeriodSelect.addEventListener("change", function () {
        const selectedPeriod = paymentPeriodSelect.value;
        const studentLrn = paymentLrnInput.value;

        // skip validation for remaining balance
        if (selectedPeriod === "Remaining Balance") {
            clearError();
            return;
        }

        if (selectedPeriod && studentLrn) {
            $.ajax({
                url: "{{ route('check.payment.period') }}",
                method: "GET",
                data: {
                    studentLrn: studentLrn,
                    paymentPeriod: selectedPeriod,
                },
                success: function (response) {
                    if (response.alreadyPaid) {
                        showError(
                            "This student has already paid for the selected transaction period."
                        );
                        paymentPeriodSelect.value = "- Transaction Period -";
                    } else {
                        clearError();
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error checking payment period:", error);
                },
            });
        } else {
            clearError();
        }
    });

    form.addEventListener("submit", function (event) {
        const selectedPeriod = paymentPeriodSelect.value;
        const studentLrn = paymentLrnInput.value;

        if (selectedPeriod === "- Transaction Period -" || !selectedPeriod) {
            showError(
                "Please select a valid transaction period before proceeding."
            );
            event.preventDefault();
            return;
        }

        if (selectedPeriod === "Remaining Balance") {
            clearError();
            return;
        }

        if (selectedPeriod && studentLrn) {
            $.ajax({
                url: "{{ route('check.payment.period') }}",
                method: "GET",
                async: false,
                data: {
                    studentLrn: studentLrn,
                    paymentPeriod: selectedPeriod,
                },
                success: function (response) {
                    if (response.alreadyPaid) {
                        showError(
                            "This student has already paid for the selected transaction period."
                        );
                        event.preventDefault();
                    } else {
                        clearError();
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error checking payment period:", error);
                },
            });
        }
    });
});
