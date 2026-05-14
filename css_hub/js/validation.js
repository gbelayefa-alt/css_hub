/*
 * Name: Harshini Lakshman and Amanda Gbe
 * Date: March 31st, 2026
 * Description: JavaScript for the suggestion page and merchandise ordering.
 */

/**
 * Initializes event listeners for the form selection dropdown when the DOM is ready.
 * @returns {void}
 */
document.addEventListener("DOMContentLoaded", function () {
    const formSelect = document.getElementById("formSelect");

    /**
     * Validates that all required fields in a form are filled out.
     * @returns {boolean} True if valid, false if any required field is empty.
     */
    function validateForm() {
        const formError = document.getElementById("formError");
        const inputs = document.querySelectorAll("input[required]");
        for (let input of inputs) {
            if (input.value.trim() === "") {
                if (formError) {
                    formError.innerHTML = "<p>Please fill in all fields</p>";
                }
                return false;
            }
        }
        if (formError) {
            formError.innerHTML = "";
        }
        return true;
    }

    /**
     * Validates that all suggestion fields are filled out.
     * @returns {boolean} True if subject and message are filled, false if any required field is empty.
     */
    function validateSuggestion() {
        const subject = document.querySelector("input[name='subject']");
        const message = document.querySelector("textarea[name='message']");
        const suggestionError = document.getElementById("suggestionError");

        if (!subject || !message) return true; // prevent crash on pages without these

        if (subject.value.trim() === "" || message.value.trim() === "") {
            if (suggestionError) {
                suggestionError.innerHTML = "<p>Please fill in all fields</p>";
            }
            return false;
        }
        return true;
    }

    if (formSelect) {
        formSelect.addEventListener("change", function () {
            console.log("formSelect:", formSelect);
            let formId = this.value;
            if (formId === "") {
                document.getElementById("formContainer").innerHTML = "";
                return;
            }
            fetch("../handlers/load_forms.php?form_id=" + formId)
                .then(response => response.text())
                .then(data => {
                    document.getElementById("formContainer").innerHTML = data;
                });
        });
    }
});

/**
 * Merchandise ordering: attaches click listeners to merch cards.
 * When a card is clicked, it becomes active and its value is stored in a hidden input.
 */
document.querySelectorAll(".merch-card").forEach(card => {
    card.addEventListener("click", function () {
        // remove active from all
        document.querySelectorAll(".merch-card")
            .forEach(c => c.classList.remove("active"));
        // highlight selected
        this.classList.add("active");
        // set hidden input
        document.getElementById("selectedItem").value = this.dataset.value;
    });
});