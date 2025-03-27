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

function customDateFilter() {
    const startDate = document.querySelector("#start_datse").value;
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

const websiteSelector = document.getElementById("websites");
let website = urlParams.get("domain") || websiteSelector.value;

websiteSelector.addEventListener("change", (event) => {
    website = event.target.value;

    window.location.href = `/article-visits?precision=${precision}&domain=${website}`;
});
