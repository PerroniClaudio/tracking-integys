import axios from "axios";

const csrf_token = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

const searchBox = document.querySelector("#search-box");
const resultsContainer = document.querySelector("#results-container");
const resultsCardTemplateSkeleton = document.querySelector(
    "#results-card-template-skeleton"
);
const resultsCardTemplate = document.querySelector("#results-card-template");

const showSkeleton = function () {
    resultsContainer.innerHTML =
        resultsCardTemplateSkeleton.innerHTML +
        resultsCardTemplateSkeleton.innerHTML +
        resultsCardTemplateSkeleton.innerHTML +
        resultsCardTemplateSkeleton.innerHTML;

    resultsContainer.classList.remove("hidden");
    resultsContainer.classList.add("grid");
};

const hideSkeleton = function () {
    resultsContainer.innerHTML = "";
    resultsContainer.classList.remove("grid");
    resultsContainer.classList.add("hidden");
};

searchBox.addEventListener("input", function () {
    const searchText = this.value.toLowerCase();

    if (searchText.length > 3) {
        showSkeleton();

        axios
            .get(`/search-user-activity?search=${searchText}`, {
                headers: {
                    "X-CSRF-TOKEN": csrf_token,
                    Accept: "application/json",
                },
                withCredentials: true, // Invia i cookie di sessione con la richiesta
            })
            .then((response) => {
                let content = "";

                response.data.forEach((element) => {
                    let badgesHtml = "";

                    element.domains.forEach((domain) => {
                        badgesHtml += `<span class="badge badge-primary">${domain}</span>`;
                    });

                    content += resultsCardTemplate.innerHTML
                        .replace(/@email/g, element.email)
                        .replace(/@domains/g, badgesHtml)
                        .replace(
                            /@initials/g,
                            element.email
                                .replace(/\./g, "")
                                .slice(0, 2)
                                .toUpperCase()
                        );
                });

                resultsContainer.innerHTML = content;
            })
            .catch((error) => {
                console.error("Error fetching data:", error);
                hideSkeleton();
            });
    } else {
        hideSkeleton();
    }
});
