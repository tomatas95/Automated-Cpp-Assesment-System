document.addEventListener('DOMContentLoaded', (event) => {
    const toggler = document.querySelector('.navbar-toggler');
    const menu = document.getElementById('mobileMenu');

    function toggleMenu() {
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    }

    function closeMenu(event) {
        if (menu.style.display === 'block' && !menu.contains(event.target) && !toggler.contains(event.target)) {
            menu.style.display = 'none';
        }
    }

    toggler.addEventListener('click', toggleMenu);
    document.addEventListener('click', closeMenu);
});