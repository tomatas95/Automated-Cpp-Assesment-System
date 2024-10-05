document.addEventListener('DOMContentLoaded', (event) => {
    const createForm = document.getElementById('createForm');
    if (createForm) {
        const createButton = createForm.querySelector('button[type="submit"]');
        const createButtonText = createButton.getAttribute('data-loading-text') || 'Loading...';

        createForm.addEventListener('submit', (event) => {
            disableButton(event, createButton, createButtonText, true);
        });
    }

    const editForm = document.getElementById('editForm');
    if (editForm) {
        const editButton = editForm.querySelector('button[type="submit"]');
        const editButtonText = editButton.getAttribute('data-loading-text') || 'Loading...';

        editForm.addEventListener('submit', (event) => {
            disableButton(event, editButton, editButtonText, true);
        });
    }

    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        const button = loginForm.querySelector('button[type="submit"]');
        const buttonText = button.getAttribute('data-loading-text') || 'Loading...';

        loginForm.addEventListener('submit', (event) => {
            disableButton(event, button, buttonText);
        });
    }

    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        const button = registerForm.querySelector('button[type="submit"]');
        const buttonText = button.getAttribute('data-loading-text') || 'Loading...';

        registerForm.addEventListener('submit', (event) => {
            disableButton(event, button, buttonText, true);
        });
    }

    const resetPasswordForm = document.getElementById('resetPasswordForm');
    if (resetPasswordForm) {
        const button = resetPasswordForm.querySelector('button[type="submit"]');
        const buttonText = button.getAttribute('data-loading-text') || 'Loading...';

        resetPasswordForm.addEventListener('submit', (event) => {
            disableButton(event, button, buttonText, true);
        });
    }

    const recoverPasswordForm = document.getElementById('recoverPasswordForm');
    if (recoverPasswordForm) {
        const button = recoverPasswordForm.querySelector('button[type="submit"]');
        const buttonText = button.getAttribute('data-loading-text') || 'Loading...';

        recoverPasswordForm.addEventListener('submit', (event) => {
            disableButton(event, button, buttonText, true);
        });
    }

    document.querySelectorAll('.deleteExerciseForm').forEach((form) => {
        const deleteExerciseButton = form.querySelector('button[type="submit"]');
        const deleteExerciseButtonText = deleteExerciseButton.getAttribute('data-loading-text') || 'Deleting...';

        form.addEventListener('submit', (event) => {
            disableButton(event, deleteExerciseButton, deleteExerciseButtonText, false);
        });
    });

    document.querySelectorAll('.deleteUserForm').forEach((form) => {
        const deleteUserButton = form.querySelector('button[type="submit"]');
        const deleteUserButtonText = deleteUserButton.getAttribute('data-loading-text') || 'Deleting...';

        form.addEventListener('submit', (event) => {
            disableButton(event, deleteUserButton, deleteUserButtonText, false);
        });
    });
});

function disableButton(event, button, buttonText, disabledStyling) {
    if (button) {
        button.disabled = true;
        if (disabledStyling) {
            button.classList.add('disabled-btn');
        }
        button.innerHTML = buttonText;
    }
}