document.addEventListener("DOMContentLoaded", (event) => {
    // -------- Reg 1st PW ---------
    const togglePassword = document.getElementById('passwordVisibility');
    const password = document.getElementById('password');
    if (togglePassword && password) {
        togglePassword.addEventListener('click', function(e) {
            const inputType = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', inputType);

            const icon = document.getElementById('toggleIcon');
            if (icon) {
                icon.classList.toggle('fa-eye-slash');
                icon.classList.toggle('active');
            }
        });
    }

    // -------- Reg Confirm PW ---------
    const toggleConfirmPassword = document.getElementById('passwordConfirmationVisibility');
    const passwordConfirmation = document.getElementById('password_confirmation');
    if (toggleConfirmPassword && passwordConfirmation) {
        toggleConfirmPassword.addEventListener('click', function(e) {
            const inputType = passwordConfirmation.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirmation.setAttribute('type', inputType);

            const icon = document.getElementById('toggleIconConfirmation');
            if (icon) {
                icon.classList.toggle('fa-eye-slash');
                icon.classList.toggle('active');
            }
        });
    }
    // -------- Create / Edit PW for Ex ---------
    const createExerciseForm = document.getElementById("createForm");
    if (createExerciseForm) {
        const toggleExercisePassword = document.getElementById("toggleExercisePasswordIcon");
        const exercisePassword = document.getElementById("exercise_password");
        if (toggleExercisePassword && exercisePassword) {
            toggleExercisePassword.addEventListener('click', function(e) {
                const inputType = exercisePassword.getAttribute('type') === 'password' ? 'text' : 'password';
                exercisePassword.setAttribute('type', inputType);

                toggleExercisePassword.classList.toggle('fa-eye-slash');
                toggleExercisePassword.classList.toggle('active');
            });
        }
    }

    // -------- Modal for Private ex ---------
    document.querySelectorAll('.modal').forEach((modal) => {
        const toggleIcon = modal.querySelector(`[id^="toggleExercisePasswordIcon"]`);
        const exercisePassword = modal.querySelector(`input[type="password"]`);

        if (toggleIcon && exercisePassword) {
            toggleIcon.addEventListener('click', function(e) {
                const inputType = exercisePassword.getAttribute('type') === 'password' ? 'text' : 'password';
                exercisePassword.setAttribute('type', inputType);

                toggleIcon.classList.toggle('fa-eye-slash');
                toggleIcon.classList.toggle('fa-eye');
            });
        }
    });


    // -------- Slider at index to disguise private ex's ---------
    const exerciseVisibilityToggle = document.getElementById('exerciseVisibility');
    if (exerciseVisibilityToggle) {
        exerciseVisibilityToggle.addEventListener('change', function() {
            const privateExercises = document.querySelectorAll('.private-exercise');
            if (exerciseVisibilityToggle.checked) {
                privateExercises.forEach(exercise => {
                    exercise.style.display = 'none';
                });
            } else {
                privateExercises.forEach(exercise => {
                    exercise.style.display = '';
                });
            }
        });
    }
});