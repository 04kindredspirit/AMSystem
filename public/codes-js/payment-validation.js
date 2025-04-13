document.addEventListener("DOMContentLoaded", function () {
    const paymentAmountInput = document.getElementById("paymentAmount");
    const tuitionAmountInput = document.getElementById("tuitionAmount");
    const form = document.querySelector("form");

    form.addEventListener("submit", function (event) {
        const paymentAmount = parseFloat(paymentAmountInput.value);
        const tuitionAmount = parseFloat(tuitionAmountInput.value);

        if (paymentAmount > tuitionAmount) {
            alert("Payment amount cannot exceed the tuition amount.");
            event.preventDefault();
        }
    });

    paymentAmountInput.addEventListener("input", function () {
        const paymentAmount = parseFloat(paymentAmountInput.value);
        const tuitionAmount = parseFloat(tuitionAmountInput.value);

        if (paymentAmount > tuitionAmount) {
            paymentAmountInput.setCustomValidity(
                "Payment amount cannot exceed the tuition amount."
            );
        } else {
            paymentAmountInput.setCustomValidity("");
        }
    });
});
