import Chart from "chart.js/auto";
import { SankeyController, Flow } from "chartjs-chart-sankey";
import axios from "axios";
import DismissableAlert from "./dismissable-alert";

// Registra il controller e il tipo di elemento necessari
Chart.register(SankeyController, Flow);

const urlParams = new URLSearchParams(window.location.search);
let precision = urlParams.get("precision") || "today";

const dateFilter = document.querySelector("#dateRange");
dateFilter.addEventListener("change", (event) => {
    precision = event.target.value;

    window.location.href = `/home?precision=${precision}`;
});

const customDateFilterButton = document.querySelector("#custom-date-filter");
customDateFilterButton.addEventListener("click", customDateFilter);

function customDateFilter() {
    const startDate = document.querySelector("#start_date").value;
    const endDate = document.querySelector("#end_date").value;
    let errorBox = new DismissableAlert("choose-date-modal-error-box");

    if (new Date(endDate) <= new Date(startDate)) {
        errorBox.setErrorMessage(
            "La data di fine deve essere successiva alla data di inizio."
        );
        errorBox.show();

        return;
    }

    window.location.href = `/home?precision=custom&start_date=${startDate}&end_date=${endDate}`;
}

const csrf_token = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

(async () => {
    let visitsUrl =
        precision == "custom"
            ? `/stats/visits?precision=${precision}&start_date=${urlParams.get(
                  "start_date"
              )}&end_date=${urlParams.get("end_date")}`
            : `/stats/visits?precision=${precision}`;

    axios
        .get(visitsUrl, {
            headers: {
                "X-CSRF-TOKEN": csrf_token,
                Accept: "application/json",
            },
            withCredentials: true, // Invia i cookie di sessione con la richiesta
        })
        .then((response) => {
            const data = response.data;
            new Chart(document.getElementById("andamento"), {
                type: "line",
                data: {
                    labels: data.map((row) => row.date),
                    datasets: [
                        {
                            data: data.map((row) => row.visits),
                            fill: true,
                            borderColor: "rgba(232, 71, 63, 1)",
                            backgroundColor: "rgba(232, 71, 63, 0.4)",
                        },
                    ],
                },
                options: {
                    plugins: {
                        legend: {
                            display: false,
                        },
                    },
                },
            });
        });
})();

(async () => {
    let referersUrl =
        precision == "custom"
            ? `/stats/referers?precision=${precision}&start_date=${urlParams.get(
                  "start_date"
              )}&end_date=${urlParams.get("end_date")}`
            : `/stats/referers?precision=${precision}`;

    axios
        .get(referersUrl, {
            headers: {
                "X-CSRF-TOKEN": csrf_token,
                Accept: "application/json",
            },
            withCredentials: true, // Invia i cookie di sessione con la richiesta
        })
        .then((response) => {
            const datasource = response.data;
            const graphCanvasId = "provenienza";

            const sources = [
                "direct_traffic",
                "search_engine",
                "social_network",
                "referral",
                "email",
            ];

            const sourcesLabels = [
                "Organico",
                "Diretto",
                "Social",
                "Referral",
                "Email",
            ];

            let sourceData = sources.map((source) => {
                let sourceValue = 0;

                datasource.forEach((row) => {
                    console.log(row);
                    if (row.source === source) {
                        sourceValue = row.value;
                    }
                });

                return sourceValue;
            });

            const data = {
                labels: sourcesLabels,
                datasets: [
                    {
                        data: sourceData,
                        backgroundColor: [
                            "rgba(255, 99, 132, 0.2)",
                            "rgba(54, 162, 235, 0.2)",
                            "rgba(255, 206, 86, 0.2)",
                            "rgba(75, 192, 192, 0.2)",
                            "rgba(153, 102, 255, 0.2)",
                        ],
                        borderColor: [
                            "rgba(255, 99, 132, 1)",
                            "rgba(54, 162, 235, 1)",
                            "rgba(255, 206, 86, 1)",
                            "rgba(75, 192, 192, 1)",
                            "rgba(153, 102, 255, 1)",
                        ],
                        borderWidth: 1,
                    },
                ],
            };

            new Chart(document.getElementById(graphCanvasId), {
                type: "pie",
                data: data,
                options: {
                    plugins: {
                        legend: {
                            display: false,
                        },
                    },
                },
            });
        });
})();

(async () => {
    let mostVisitedUrl =
        precision == "custom"
            ? `/stats/most-visited?precision=${precision}&start_date=${urlParams.get(
                  "start_date"
              )}&end_date=${urlParams.get("end_date")}`
            : `/stats/most-visited?precision=${precision}`;

    const urlSources = [
        // "https://news.integys.com/",
        // "https://news.integys.com/news/",
        // "https://www.dpodelcomune.com/",
        // "http://localhost:3000/",
    ];

    axios
        .get(mostVisitedUrl, {
            headers: {
                "X-CSRF-TOKEN": csrf_token,
                Accept: "application/json",
            },
            withCredentials: true, // Invia i cookie di sessione con la richiesta
        })
        .then((response) => {
            const datasource = response.data;
            const graphCanvasId = "piuvisitate";

            datasource.sort((a, b) => b.value - a.value);

            let labels = datasource.map((row) => {
                let url = row.url;
                urlSources.forEach((source) => {
                    if (url.startsWith(source)) {
                        url = url.replace(source, "");
                    }
                });

                if (url.length > 30) {
                    url = url.substring(0, 30) + "...";
                }

                return url;
            });

            const data = {
                labels: labels,
                datasets: [
                    {
                        data: datasource.map((row) => row.value),
                        backgroundColor: "rgba(232, 71, 63, 0.4)",
                        borderColor: "rgba(232, 71, 63, 1)",
                        borderWidth: 1,
                    },
                ],
            };

            new Chart(document.getElementById(graphCanvasId), {
                type: "bar",
                data: data,
                options: {
                    indexAxis: "y",
                    scales: {
                        x: {
                            beginAtZero: true,
                        },
                    },

                    plugins: {
                        legend: {
                            display: false,
                        },
                    },
                },
            });
        });
})();

(async () => {
    const ctx = document.getElementById("navigationFlowChart").getContext("2d");
    const chart = new Chart(ctx, {
        type: "sankey",
        data: {
            datasets: [
                {
                    data: [
                        // Da -> A -> Valore (percentuale o numero di utenti)
                        {
                            from: "Homepage",
                            to: "Prodotti",
                            flow: 1240,
                        },
                        { from: "Homepage", to: "Chi siamo", flow: 775 },
                        { from: "Homepage", to: "Uscita", flow: 620 },
                        { from: "Prodotti", to: "Prodotto A", flow: 682 },
                        { from: "Prodotti", to: "Prodotto B", flow: 372 },
                        { from: "Prodotti", to: "Uscita", flow: 186 },
                        { from: "Chi siamo", to: "Contatti", flow: 271 },
                        { from: "Chi siamo", to: "Uscita", flow: 504 },
                        { from: "Prodotto A", to: "Specifiche", flow: 273 },
                        { from: "Prodotto A", to: "Acquisto", flow: 307 },
                        { from: "Prodotto A", to: "Uscita", flow: 102 },
                        { from: "Prodotto B", to: "Acquisto", flow: 223 },
                        { from: "Prodotto B", to: "Uscita", flow: 149 },
                        { from: "Specifiche", to: "Uscita", flow: 273 },
                        { from: "Acquisto", to: "Grazie", flow: 530 },
                        { from: "Contatti", to: "Contatto Inviato", flow: 271 },
                    ],

                    colorFrom: (context) => {
                        return "#3498db"; // Colore blu per tutti i nodi di partenza
                    },
                    colorTo: (context) => {
                        // Differenzia i colori in base al nodo di destinazione
                        const label =
                            context.dataset.data[context.dataIndex].to;
                        if (label.includes("Uscita")) return "#e74c3c"; // Rosso per i punti di uscita
                        if (
                            label.includes("Acquisto") ||
                            label.includes("Grazie") ||
                            label.includes("Contatto")
                        )
                            return "#f39c12"; // Arancione per le conversioni
                        return "#3498db"; // Blu per le normali pagine
                    },
                    labels: {
                        color: (context) => {
                            const label =
                                context.dataset.data[context.dataIndex].from;
                            if (label === "Homepage") return "#fff"; // Bianco per "Homepage"
                            return "#000"; // Nero per gli altri
                        },
                    },
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: false,
                    text: "Percorsi di Navigazione degli Utenti",
                    font: {
                        size: 18,
                    },
                },
                tooltip: {
                    callbacks: {
                        title: function (context) {
                            return `${
                                context[0].dataset.data[context[0].dataIndex]
                                    .from
                            } → ${
                                context[0].dataset.data[context[0].dataIndex].to
                            }`;
                        },
                        label: function (context) {
                            const data =
                                context.dataset.data[context.dataIndex];
                            const percentage = (
                                (data.flow / 3100) *
                                100
                            ).toFixed(1);
                            return `Utenti: ${data.flow} (${percentage}%)`;
                        },
                    },
                },
                legend: {
                    display: false,
                },
            },
            layout: {
                padding: 20,
            },
        },
    });
})();
