document.addEventListener('DOMContentLoaded', function() {
    const languageList = document.getElementById('languageDropdown');
    languageList.addEventListener('click', function() {
        this.classList.toggle('dropdown-open');
    })

    const myAccountMenu = document.getElementById('accountDropdown');
    if (myAccountMenu != null) {
        myAccountMenu.addEventListener('click', function() {
            this.classList.toggle('dropdown-open');
        });
    }
});