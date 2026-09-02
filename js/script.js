// =========================================================
// PLACEMENT DRIVE SYSTEM - SHARED HELPERS
// Loaded on every page. No popups are used for validation.
// =========================================================

// =========================================================
// SHOW ERROR NEXT TO FIELD
// =========================================================
function showError(input, message) {
    input.classList.add("input-error");
    input.classList.remove("input-success");

    const errorMessage = input.parentElement.querySelector(".error-message");
    if (errorMessage) {
        errorMessage.textContent = message;
    }
}

// =========================================================
// CLEAR ERROR / MARK VALID
// =========================================================
function clearError(input) {
    input.classList.remove("input-error");
    input.classList.add("input-success");

    const errorMessage = input.parentElement.querySelector(".error-message");
    if (errorMessage) {
        errorMessage.textContent = "";
    }
}

// =========================================================
// RESET FIELD STATE
// =========================================================
function resetField(input) {
    input.classList.remove("input-error", "input-success");
    const errorMessage = input.parentElement.querySelector(".error-message");
    if (errorMessage) {
        errorMessage.textContent = "";
    }
}

// =========================================================
// GENERIC VALIDATORS (reused across officer + student forms)
// =========================================================
function validateRequiredText(input, label, minLength = 2) {
    const value = input.value.trim();

    if (value === "") {
        showError(input, label + " cannot be empty.");
        return false;
    }

    if (value.length < minLength) {
        showError(input, "Enter a valid " + label.toLowerCase() + ".");
        return false;
    }

    clearError(input);
    return true;
}

function validateNumberRange(input, label, min, max) {
    const value = input.value.trim();

    if (value === "") {
        showError(input, label + " cannot be empty.");
        return false;
    }

    const num = Number(value);

    if (isNaN(num)) {
        showError(input, label + " must be a number.");
        return false;
    }

    if (min !== null && num < min) {
        showError(input, label + " cannot be less than " + min + ".");
        return false;
    }

    if (max !== null && num > max) {
        showError(input, label + " cannot be more than " + max + ".");
        return false;
    }

    clearError(input);
    return true;
}

function validatePositiveNumber(input, label) {
    const value = input.value.trim();

    if (value === "") {
        showError(input, label + " cannot be empty.");
        return false;
    }

    const num = Number(value);

    if (isNaN(num)) {
        showError(input, label + " must be a number.");
        return false;
    }

    if (num <= 0) {
        showError(input, label + " must be greater than 0.");
        return false;
    }

    clearError(input);
    return true;
}

function validateWholeNumber(input, label, min = 0) {
    const value = input.value.trim();

    if (value === "") {
        showError(input, label + " cannot be empty.");
        return false;
    }

    const num = Number(value);

    if (isNaN(num)) {
        showError(input, label + " must be a number.");
        return false;
    }

    if (num < min) {
        showError(input, label + " cannot be negative.");
        return false;
    }

    if (!Number.isInteger(num)) {
        showError(input, label + " must be a whole number.");
        return false;
    }

    clearError(input);
    return true;
}

function validateSelect(input, label) {
    if (input.value === "") {
        showError(input, "Please select a " + label.toLowerCase() + ".");
        return false;
    }
    clearError(input);
    return true;
}

function validateDate(input, label) {
    if (input.value === "") {
        showError(input, label + " is required.");
        return false;
    }
    clearError(input);
    return true;
}

function validatePdfFile(input) {
    const file = input.files[0];

    if (!file) {
        showError(input, "Resume is required.");
        return false;
    }

    const fileName = file.name.toLowerCase();

    if (!fileName.endsWith(".pdf")) {
        showError(input, "Resume must be a PDF file.");
        return false;
    }

    clearError(input);
    return true;
}

// =========================================================
// SMALL FORMAT HELPERS
// =========================================================
function formatDate(dateString) {
    if (!dateString) return "-";
    const date = new Date(dateString);
    if (isNaN(date)) return dateString;
    return date.toLocaleDateString("en-IN", { day: "2-digit", month: "short", year: "numeric" });
}

function badgeClassForStatus(status) {
    const map = {
        "Open": "badge-open",
        "Closed": "badge-closed",
        "Applied": "badge-applied",
        "Shortlisted": "badge-shortlisted",
        "Rejected": "badge-rejected"
    };
    return map[status] || "badge-open";
}
