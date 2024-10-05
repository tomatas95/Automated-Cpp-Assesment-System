document.addEventListener('DOMContentLoaded', function() {
    const tableRows = document.querySelectorAll('.ex-style-hover');

    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            const stretchedLink = row.querySelector('.stretched-link');
            if (stretchedLink) {
                stretchedLink.style.color = '#1DA09C';
            }
            row.style.cursor = 'pointer';
        });

        row.addEventListener('mouseleave', function() {
            const stretchedLink = row.querySelector('.stretched-link');
            if (stretchedLink) {
                stretchedLink.style.color = '';
            }
        });

        row.addEventListener('click', function(event) {
            if (event.target.closest('.btn-group a, .btn-group button')) {
                return;
            }

            if (event.target.closest('a')) {
                event.preventDefault();
            }

            const exerciseRow = event.target.closest('.ex-style-hover');

            if (exerciseRow) {
                const exerciseVisibility = exerciseRow.classList.contains('private-exercise');
                if (exerciseVisibility) {
                    const exerciseModalPopup = exerciseRow.querySelector('[data-target]').getAttribute('data-target');
                    const modalPopup = document.querySelector(exerciseModalPopup);
                    if (modalPopup && !modalPopup.classList.contains('show')) {
                        $(modalPopup).modal('show');
                    }
                } else {
                    const exercise = exerciseRow.querySelector('.stretched-link').getAttribute('href');
                    if (exercise) {
                        window.location.href = exercise;
                    }
                }
            }
        });

        const modalPopup = row.querySelector('[data-target]');
        if (modalPopup) {
            const exerciseModalPopup = modalPopup.getAttribute('data-target');
            const modalElement = document.querySelector(exerciseModalPopup);
            if (modalElement) {
                const cancelBtn = modalElement.querySelector('.btn-secondary');
                const closeBtn = modalElement.querySelector('.close');

                if (cancelBtn) {
                    cancelBtn.addEventListener('click', function() {
                        $(modalElement).modal('hide');
                    });
                }

                if (closeBtn) {
                    closeBtn.addEventListener('click', function() {
                        $(modalElement).modal('hide');
                    });
                }
            }
        }
    });
});