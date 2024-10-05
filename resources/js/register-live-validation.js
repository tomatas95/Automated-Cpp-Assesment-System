document.addEventListener("DOMContentLoaded", function() {
    const validateData = (input, regex, errorMessage) => {
        const helperText = input.closest('.form-group').querySelector('.helper-text');
        if (regex.test(input.value.trim())) {
            input.classList.remove("is-invalid");
            input.classList.add("is-valid");
            if (helperText) {
                helperText.classList.remove("text-danger");
                helperText.classList.add("text-success");
                helperText.innerText = jsvalidi18.correct_validation;
            }
            return true;
        } else {
            input.classList.remove("is-valid");
            input.classList.add("is-invalid");
            if (helperText) {
                helperText.classList.remove("text-success");
                helperText.classList.add("text-danger");
                helperText.innerText = errorMessage;
            }
            return false;
        }
    };

    const validateRegistrationForm = () => {
        const form = document.getElementById("registerForm");
        if (!form) {
            return;
        }
        const submitButton = form.querySelector("button[type='submit']");

        const usernameInput = document.getElementById("name");
        const emailInput = document.getElementById("email");
        const passwordInput = document.getElementById("password");
        const passwordConfirmationInput = document.getElementById("password_confirmation");

        const checkFormValidity = () => {
            const isUsernameValid = validateData(
                usernameInput,
                /^(?=.*[A-Z])[A-Za-z0-9]{3,255}$/,
                jsvalidi18.username_validation
            );
            const isEmailValid = validateData(
                emailInput,
                /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                jsvalidi18.email_validation
            );
            const isPasswordValid = validateData(
                passwordInput,
                /^(?=.*[0-9]).{5,255}$/,
                jsvalidi18.password_validation
            );
            const isPasswordConfirmationValid = validateData(
                passwordConfirmationInput,
                new RegExp(`^${passwordInput.value}$`),
                jsvalidi18.pass_match_validation
            );

            submitButton.disabled = !(isUsernameValid && isEmailValid && isPasswordValid && isPasswordConfirmationValid);
        };

        usernameInput.addEventListener("input", function() {
            validateData(usernameInput, /^(?=.*[A-Z])[A-Za-z0-9]{3,255}$/, jsvalidi18.username_validation);
            checkFormValidity();
        });

        emailInput.addEventListener("input", function() {
            validateData(emailInput, /^[^\s@]+@[^\s@]+\.[^\s@]+$/, jsvalidi18.email_validation);
            checkFormValidity();
        });

        passwordInput.addEventListener("input", function() {
            validateData(passwordInput, /^(?=.*[0-9]).{5,255}$/, jsvalidi18.password_validation);
            validateData(passwordConfirmationInput, new RegExp(`^${passwordInput.value}$`), jsvalidi18.pass_match_validation);
            checkFormValidity();
        });

        passwordConfirmationInput.addEventListener("input", function() {
            validateData(passwordConfirmationInput, new RegExp(`^${passwordInput.value}$`), jsvalidi18.pass_match_validation);
            checkFormValidity();
        });

        form.addEventListener("submit", function(event) {
            submitButton.innerHTML = submitButton.getAttribute("data-loading-text") || "Submitting...";
        });
    };

    const validateResetPasswordForm = () => {
        const form = document.getElementById("resetPasswordForm");
        if (!form) {
            return;
        }

        const submitButton = form.querySelector("button[type='submit']");
        const passwordInput = document.getElementById("password");
        const passwordConfirmationInput = document.getElementById("password_confirmation");

        const checkFormValidity = () => {
            const isPasswordValid = validateData(
                passwordInput,
                /^(?=.*[0-9]).{5,255}$/,
                jsvalidi18.password_validation
            );
            const isPasswordConfirmationValid = validateData(
                passwordConfirmationInput,
                new RegExp(`^${passwordInput.value}$`),
                jsvalidi18.pass_match_validation
            );

            submitButton.disabled = !(isPasswordValid && isPasswordConfirmationValid);
        };

        passwordInput.addEventListener("input", function() {
            validateData(passwordInput, /^(?=.*[0-9]).{5,255}$/, jsvalidi18.password_validation);
            validateData(passwordConfirmationInput, new RegExp(`^${passwordInput.value}$`), jsvalidi18.pass_match_validation);
            checkFormValidity();
        });

        passwordConfirmationInput.addEventListener("input", function() {
            validateData(passwordConfirmationInput, new RegExp(`^${passwordInput.value}$`), jsvalidi18.pass_match_validation);
            checkFormValidity();
        });

        form.addEventListener("submit", function(event) {
            submitButton.innerHTML = submitButton.getAttribute("data-loading-text") || "Saving...";
        });
    };

    const validateProfileEditForm = () => {
        const form = document.getElementById("profileEdit");
        if (!form) {
            return;
        }
        const nameInput = document.getElementById("name");
        const emailInput = document.getElementById("email");
        const submitButton = document.getElementById('submit-profile-btn');

        const checkFormValidity = () => {
            const isNameValid = validateData(
                nameInput,
                /^(?=.*[A-Z])[A-Za-z0-9]{3,80}$/,
                `${jsvalidi18.username_validation}`
            );

            const isEmailValid = validateData(
                emailInput,
                /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                `${jsvalidi18.email_validation}`
            );

            submitButton.disabled = !(isNameValid && isEmailValid);
        };

        nameInput.addEventListener("input", function() {
            validateData(nameInput, /^(?=.*[A-Z])[A-Za-z0-9]{3,255}$/, `${jsvalidi18.username_validation}`);
            checkFormValidity();
        });

        emailInput.addEventListener("input", function() {
            validateData(emailInput, /^[^\s@]+@[^\s@]+\.[^\s@]+$/, `${jsvalidi18.email_validation}`);
            checkFormValidity();
        });
    };

    validateRegistrationForm();
    validateResetPasswordForm();
    validateProfileEditForm();
});