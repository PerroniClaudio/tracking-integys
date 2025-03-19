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
    let mostVisitedUrl =
        precision == "custom"
            ? `/stats/most-visited?precision=${precision}&domain=${website}&start_date=${urlParams.get(
                  "start_date"
              )}&end_date=${urlParams.get("end_date")}`
            : `/stats/most-visited?precision=${precision}&domain=${website}`;

    const urlSources = [
        "https://news.integys.com/",
        "https://news.integys.com/news/",
        "https://www.dpodelcomune.com/",
        "http://localhost:3000/",
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
                        backgroundColor: "rgba(118, 115, 143, 0.4)",
                        borderColor: "rgba(118, 115, 143, 1)",
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
    let referersUrl =
        precision == "custom"
            ? `/stats/referers?precision=${precision}&domain=${website}&start_date=${urlParams.get(
                  "start_date"
              )}&end_date=${urlParams.get("end_date")}`
            : `/stats/referers?precision=${precision}&domain=${website}`;

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
    let visitsUrl =
        precision == "custom"
            ? `https://tracking.integys.test/stats/bounce-rate?precision=${precision}&start_date=${urlParams.get(
                  "start_date"
              )}&end_date=${urlParams.get("end_date")}&domain=${website}`
            : `https://tracking.integys.test/stats/bounce-rate?precision=${precision}&domain=${website}`;

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
            new Chart(document.getElementById("bounce_rate"), {
                type: "line",
                data: {
                    labels: data.map((row) => row.date),
                    datasets: [
                        {
                            data: data.map((row) => row.rate),
                            fill: true,
                            borderColor: "rgba(118, 115, 143, 1)",
                            backgroundColor: "rgba(118, 115, 143, 0.4)",
                        },
                    ],
                },
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    let label = context.dataset.label || "";
                                    if (label) {
                                        label += ": ";
                                    }
                                    label += context.raw + "%";
                                    return label;
                                },
                            },
                        },
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

(async () => {
    let visitsUrl =
        precision == "custom"
            ? `https://tracking.integys.test/stats/devices?precision=${precision}&start_date=${urlParams.get(
                  "start_date"
              )}&end_date=${urlParams.get("end_date")}&domain=${website}`
            : `https://tracking.integys.test/stats/devices?precision=${precision}&domain=${website}`;

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

            let browsers = data.browsers;
            let devices = data.devices;
            let operating_systems = data.operating_systems;

            new Chart(document.getElementById("devices"), {
                type: "bar",
                data: {
                    labels: Object.keys(devices),
                    datasets: [
                        {
                            label: "Devices",
                            data: Object.values(devices),
                            backgroundColor: "rgba(118, 115, 143, 0.4)",
                            borderColor: "rgba(118, 115, 143, 1)",
                            borderWidth: 1,
                        },
                    ],
                },
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

            new Chart(document.getElementById("browsers"), {
                type: "bar",
                data: {
                    labels: Object.keys(browsers),
                    datasets: [
                        {
                            label: "Browsers",
                            data: Object.values(browsers),
                            backgroundColor: "rgba(118, 115, 143, 0.4)",
                            borderColor: "rgba(118, 115, 143, 1)",
                            borderWidth: 1,
                        },
                    ],
                },
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

            new Chart(document.getElementById("os"), {
                type: "bar",
                data: {
                    labels: Object.keys(operating_systems),
                    datasets: [
                        {
                            label: "OS",
                            data: Object.values(operating_systems),
                            backgroundColor: "rgba(118, 115, 143, 0.4)",
                            borderColor: "rgba(118, 115, 143, 1)",
                            borderWidth: 1,
                        },
                    ],
                },
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
    let visitsUrl =
        precision == "custom"
            ? `https://tracking.integys.test/stats/provenance?precision=${precision}&start_date=${urlParams.get(
                  "start_date"
              )}&end_date=${urlParams.get("end_date")}&domain=${website}`
            : `https://tracking.integys.test/stats/provenance?precision=${precision}&domain=${website}`;

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
                            backgroundColor: "rgba(118, 115, 143, 0.4)",
                            borderColor: "rgba(118, 115, 143, 1)",
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
                            backgroundColor: "rgba(118, 115, 143, 0.4)",
                            borderColor: "rgba(118, 115, 143, 1)",
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
