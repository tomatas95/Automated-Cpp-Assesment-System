document.addEventListener('DOMContentLoaded', function() {
    const difficultyText = document.querySelector('.difficulty-level');
    const difficultyIcon = document.querySelector('.difficulty-icon');

    const difficulty = difficultyText.getAttribute('data-difficulty').toLowerCase();

    let iconSrc;
    let color;

    switch (difficulty) {
        case 'easy':
            iconSrc = EASY_ICON;
            color = '#6DA34D';
            break;
        case 'normal':
            iconSrc = NORMAL_ICON;
            color = '#007bff';
            break;
        case 'hard':
            iconSrc = HARD_ICON;
            color = '#E82929';
            break;
        default:
            iconSrc = NORMAL_ICON;
            color = '#007bff';
    }

    difficultyIcon.src = iconSrc;
    difficultyText.style.color = color;
});