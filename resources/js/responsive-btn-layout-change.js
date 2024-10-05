document.addEventListener("DOMContentLoaded", function() {
    if (window.innerWidth <= 1000) {
        let editBtn = document.getElementById("editBtn");
        let createdExercisesBtn = document.getElementById('createdExBtn');

        let descriptionRow = document.getElementById("descRow");
        let copyright = document.getElementById('copyright-responsive');
        let outputContainer = document.getElementById('responsive-copyright-wrapper');

        if (editBtn && createdExercisesBtn && descriptionRow) {
            descriptionRow.appendChild(editBtn);
            descriptionRow.appendChild(createdExercisesBtn);
        }

        if (copyright && outputContainer) {
            outputContainer.insertAdjacentElement('afterend', copyright);
        }
    }
});