export default class DismissableAlert {
    constructor(id) {
        this.id = id;
        this.alert = document.getElementById(this.id);
        this.closeButton = this.alert.querySelector(".dismiss-error");

        this.closeButton.addEventListener("click", () => this.hide());
    }

    init() {
        this.closeButton.addEventListener("click", () => {
            this.alert.classList.add("hidden");
        });
    }

    show() {
        this.alert.classList.remove("hidden");
        this.alert.classList.add("block");
    }

    hide() {
        this.alert.classList.add("hidden");
        this.alert.classList.remove("block");
    }

    setErrorMessage(message) {
        this.alert.querySelector(".alert-message-content").innerText = message;
    }
}
