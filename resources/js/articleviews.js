import Chart from "chart.js/auto";
import axios from "axios";

const urlParams = new URLSearchParams(window.location.search);
let precision = urlParams.get("precision") || "today";

const dateFilter = document.querySelector("#dateRange");
dateFilter.addEventListener("change", (event) => {
    precision = event.target.value;

    const url = new URL(window.location.href);
    url.searchParams.set("precision", precision);
    window.location.href = url.toString();
});

const customDateFilterButton = document.querySelector("#custom-date-filter");
customDateFilterButton.addEventListener("click", customDateFilter);
let domain = document.getElementById("domain").value;

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

    const url = new URL(window.location.href);
    url.searchParams.set("precision", "custom");
    url.searchParams.set("start_date", startDate);
    url.searchParams.set("end_date", endDate);
    window.location.href = url.toString();
}

const csrf_token = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

(async () => {
    let visitsUrl =
        precision == "custom"
            ? `/stats/visits?domain=${domain}&should_show_articles_only=true&precision=${precision}&start_date=${urlParams.get(
                  "start_date"
              )}&end_date=${urlParams.get("end_date")}`
            : `/stats/visits?domain=${domain}&should_show_articles_only=true&precision=${precision}`;

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
    let visitsUrl =
        precision == "custom"
            ? `/stats/provenance?precision=${precision}&start_date=${urlParams.get(
                  "start_date"
              )}&end_date=${urlParams.get("end_date")}&domain=${domain}`
            : `/stats/provenance?precision=${precision}&domain=${domain}`;

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

            let nations = data.nations;
            let cities = data.cities;

            new Chart(document.getElementById("cities"), {
                type: "bar",
                data: {
                    labels: Object.keys(cities),
                    datasets: [
                        {
                            label: "",
                            data: Object.values(cities),
                            backgroundColor: "rgba(232, 71, 63, 0.4)",
                            borderColor: "rgba(232, 71, 63, 1)",
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    indexAxis: "y",
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                            },
                        },
                    },

                    plugins: {
                        legend: {
                            display: false,
                        },
                    },
                },
            });

            new Chart(document.getElementById("nations"), {
                type: "bar",
                data: {
                    labels: Object.keys(nations),
                    datasets: [
                        {
                            label: "Nazioni",
                            data: Object.values(nations),
                            backgroundColor: "rgba(232, 71, 63, 0.4)",
                            borderColor: "rgba(232, 71, 63, 1)",
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    indexAxis: "y",
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                            },
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
