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

function createPieChart(canvasId, data, balance) {
    const ctx = document.getElementById(canvasId).getContext("2d");
    new Chart(ctx, {
        type: "pie",
        data: {
            labels: data.map((item) => item.description),
            datasets: [
                {
                    data: data.map((item) => item.amount),
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
                    text: `Remaining Balance: ${balance}`,
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

createPieChart("pieUtilities", utilitiesData, utilitiesBalance);
createPieChart("pieSalaries", salariesData, salariesBalance);
createPieChart("piePettyCash", pettyCashData, pettyCashBalance);
createPieChart("pieMaintenance", maintenanceData, maintenanceBalance);
createPieChart("pieSupplies", suppliesData, suppliesBalance);
