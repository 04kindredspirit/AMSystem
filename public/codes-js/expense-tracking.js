function generateColors(count) {
    const colors = [];

    function shadesOfBlack(hex) {
        const r = parseInt(hex.substring(1, 3), 16);
        const g = parseInt(hex.substring(3, 5), 16);
        const b = parseInt(hex.substring(5, 7), 16);
        return 0.299 * r + 0.587 * g + 0.114 * b;
    }

    while (colors.length < count) {
        let color = `#${Math.floor(Math.random() * 16777215)
            .toString(16)
            .padStart(6, "0")}`;
        const shade = shadesOfBlack(color);

        if (shade > 40) {
            colors.push(color);
        }
    }

    return colors;
}

// update category title based on budget
function updateCategoryTitleColor(canvasId, remainingBalance, totalAllocated) {
    if (!totalAllocated || totalAllocated === 0) return;

    // calculate for percentage of remaining budget
    const percentRemaining = (remainingBalance / totalAllocated) * 100;

    const canvas = document.getElementById(canvasId);
    const card = canvas.closest(".card");
    const cardHeader = card.querySelector(".card-header");
    const cardTitle = cardHeader.querySelector(".card-title");

    // if there are existing color for the titles this will remove it
    cardTitle.classList.remove(
        "text-danger",
        "text-warning",
        "text-success",
        "text-white"
    );

    // if there are existing color for the headers this will remove it
    cardHeader.classList.remove(
        "bg-danger",
        "bg-warning",
        "bg-success",
        "bg-primary",
        "bg-info"
    );

    // applying the text color changing
    if (percentRemaining <= 10) {
        cardTitle.classList.add("text-danger");
    } else if (percentRemaining <= 50) {
        cardTitle.classList.add("text-warning");
    } else {
        cardTitle.classList.add("text-success");
    }
}

function createExpensePieChart(canvasId, data, balance, totalAllocated) {
    const ctx = document.getElementById(canvasId).getContext("2d");

    updateCategoryTitleColor(canvasId, balance, totalAllocated);

    const sortedData = [...data].sort((a, b) => b.amount - a.amount);

    new Chart(ctx, {
        type: "pie",
        data: {
            labels: sortedData.map((item) => {
                const shortDesc =
                    item.description.length > 15
                        ? item.description.substring(0, 12) + "..."
                        : item.description;
                return `${shortDesc}`;
            }),
            datasets: [
                {
                    data: sortedData.map((item) => item.amount),
                    backgroundColor: generateColors(data.length),
                    hoverOffset: 4,
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: `Remaining Budget: ₱${parseFloat(
                        balance
                    ).toLocaleString()}`,
                    font: {
                        size: 16,
                    },
                },
                legend: {
                    display: false,
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce(
                                (a, b) => a + b,
                                0
                            );
                            const amountText = `₱${value.toLocaleString()}`;

                            return total > 0
                                ? `${amountText} (${Math.round(
                                      (value / total) * 100
                                  )}%)`
                                : amountText;
                        },
                        title: function (context) {
                            return context[0].label
                                .replace(/\(\$[^)]*\)/, "")
                                .trim();
                        },
                    },
                },
            },
        },
    });
}

// if the pie chart has no data show the remaining budget instead
function createEmptyExpensePieChart(canvasId, balance, totalAllocated) {
    const ctx = document.getElementById(canvasId).getContext("2d");

    updateCategoryTitleColor(canvasId, balance, totalAllocated);

    new Chart(ctx, {
        type: "pie",
        data: {
            labels: ["No recent expenses"],
            datasets: [
                {
                    data: [1],
                    backgroundColor: ["#6c757d"],
                    hoverOffset: 4,
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: `Remaining Budget: ₱${parseFloat(
                        balance
                    ).toLocaleString()}`,
                    font: {
                        size: 16,
                    },
                },
                legend: {
                    display: false,
                },
            },
        },
    });
}

// creation of charts with validation
document.addEventListener("DOMContentLoaded", function () {
    if (utilitiesData && utilitiesData.length > 0) {
        createExpensePieChart(
            "pieUtilities",
            utilitiesData,
            utilitiesBalance,
            utilitiesTotalAllocated
        );
    } else {
        createEmptyExpensePieChart(
            "pieUtilities",
            utilitiesBalance,
            utilitiesTotalAllocated
        );
    }

    if (salariesData && salariesData.length > 0) {
        createExpensePieChart(
            "pieSalaries",
            salariesData,
            salariesBalance,
            salariesTotalAllocated
        );
    } else {
        createEmptyExpensePieChart(
            "pieSalaries",
            salariesBalance,
            salariesTotalAllocated
        );
    }

    if (pettyCashData && pettyCashData.length > 0) {
        createExpensePieChart(
            "piePettyCash",
            pettyCashData,
            pettyCashBalance,
            pettyCashTotalAllocated
        );
    } else {
        createEmptyExpensePieChart(
            "piePettyCash",
            pettyCashBalance,
            pettyCashTotalAllocated
        );
    }

    if (maintenanceData && maintenanceData.length > 0) {
        createExpensePieChart(
            "pieMaintenance",
            maintenanceData,
            maintenanceBalance,
            maintenanceTotalAllocated
        );
    } else {
        createEmptyExpensePieChart(
            "pieMaintenance",
            maintenanceBalance,
            maintenanceTotalAllocated
        );
    }

    if (suppliesData && suppliesData.length > 0) {
        createExpensePieChart(
            "pieSupplies",
            suppliesData,
            suppliesBalance,
            suppliesTotalAllocated
        );
    } else {
        createEmptyExpensePieChart(
            "pieSupplies",
            suppliesBalance,
            suppliesTotalAllocated
        );
    }
});
