import Chart from "chart.js/auto";
import DismissableAlert from "./dismissable-alert";

const websiteSelector = document.getElementById("websites");
const precisionSelector = document.getElementById("dateRange");
const urlParams = new URLSearchParams(window.location.search);

let website = urlParams.get("website") || websiteSelector.value;
let precision = urlParams.get("precision") || precisionSelector.value;

const csrf_token = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

(async () => {
    let visitsUrl =
        precision == "custom"
            ? `https://tracking.integys.test/stats/visits-unique?precision=${precision}&start_date=${urlParams.get(
                  "start_date"
              )}&end_date=${urlParams.get("end_date")}&domain=${website}`
            : `https://tracking.integys.test/stats/visits-unique?precision=${precision}&domain=${website}`;

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
            new Chart(document.getElementById("unique_visitors"), {
                type: "line",
                data: {
                    labels: data.map((row) => row.date),
                    datasets: [
                        {
                            data: data.map((row) => row.visits),
                            fill: true,
                            borderColor: "rgba(118, 115, 143, 1)",
                            backgroundColor: "rgba(118, 115, 143, 0.4)",
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
            ? `https://tracking.integys.test/stats/visits?precision=${precision}&start_date=${urlParams.get(
                  "start_date"
              )}&end_date=${urlParams.get("end_date")}&domain=${website}`
            : `https://tracking.integys.test/stats/visits?precision=${precision}&domain=${website}`;

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
            new Chart(document.getElementById("visits_total"), {
                type: "line",
                data: {
                    labels: data.map((row) => row.date),
                    datasets: [
                        {
                            data: data.map((row) => row.visits),
                            fill: true,
                            borderColor: "rgba(118, 115, 143, 1)",
                            backgroundColor: "rgba(118, 115, 143, 0.4)",
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
