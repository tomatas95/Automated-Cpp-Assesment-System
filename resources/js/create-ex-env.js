document.addEventListener("DOMContentLoaded", function() {
    ClassicEditor
        .create(document.querySelector('#exerciseContent'), {
            toolbar: {
                items: [
                    'heading', '|',
                    'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'indent', 'outdent', '|',
                    'undo', 'redo'
                ]
            },
            removePlugins: ['MediaEmbed', 'imageUpload', 'Table', 'BlockQuote']
        })
    const hintSlider = document.getElementById('includeHints');
    const hintsContainer = document.getElementById('hintsContainer');

    const exerciseVisibilitySlider = document.getElementById('exerciseVisibility');
    const exerciseVisibilityContainer = document.getElementById('exerciseVisibilityContainer')

    hintSlider.addEventListener('change', function() {
        if (hintSlider.checked) {
            hintsContainer.style.display = 'block';
        } else {
            hintsContainer.style.display = 'none';
        }
    });

    exerciseVisibilitySlider.addEventListener('change', function() {
        if (exerciseVisibilitySlider.checked) {
            exerciseVisibilityContainer.style.display = 'block';
        } else {
            exerciseVisibilityContainer.style.display = 'none';
        }
    });

    if (exerciseVisibilitySlider.checked) {
        exerciseVisibilityContainer.style.display = 'block';
    }
});