document.addEventListener('DOMContentLoaded', function() {
    fetch('https://restcountries.com/v3.1/all')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('location');
            data.sort((a, b) => a.name.common.localeCompare(b.name.common)).forEach(country => {
                const option = document.createElement('option');
                const countryName = country.name.common;
                option.value = countryName;
                option.textContent = country_lang[countryName] || countryName;
                if (countryName === user_location) {
                    option.selected = true;
                }
                select.appendChild(option);
            });
        });

    const months = month_lang;

    const currentYear = new Date().getFullYear();

    const yearSelect = document.getElementById('year');
    const monthSelect = document.getElementById('month');
    const daySelect = document.getElementById('day');

    months.forEach((month, index) => {
        const option = document.createElement('option');
        option.value = index + 1;
        option.textContent = month;
        if (index + 1 == birth_month) {
            option.selected = true;
        }
        monthSelect.appendChild(option);
    });

    for (let i = currentYear; i >= 1900; i--) {
        const option = document.createElement('option');
        option.value = i;
        option.textContent = i;
        if (i == birth_year) {
            option.selected = true;
        }
        yearSelect.appendChild(option);
    }

    function monthDays(month, year) {
        const daysInMonth = new Date(year, month, 0).getDate();
        daySelect.innerHTML = '';

        for (let i = 1; i <= daysInMonth; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = i;
            if (i == birth_day) {
                option.selected = true;
            }
            daySelect.appendChild(option);
        }
    }

    function removeBirthDate() {
        if (yearSelect.value) {
            monthSelect.style.display = 'inline-block';
            daySelect.style.display = 'inline-block';

            const month = monthSelect.querySelector('option[value=""]');
            const day = daySelect.querySelector('option[value=""]');
            if (month) month.remove();
            if (day) day.remove();
        } else {
            monthSelect.style.display = 'none';
            daySelect.style.display = 'none';
            monthSelect.value = '';
            daySelect.value = '';
        }
    }

    removeBirthDate();

    yearSelect.addEventListener('change', () => {
        removeBirthDate();
        monthDays(monthSelect.value, yearSelect.value);
    });

    monthSelect.addEventListener('change', () => {
        monthDays(monthSelect.value, yearSelect.value);
    });

    if (yearSelect.value === '') {
        monthSelect.style.display = 'none';
        daySelect.style.display = 'none';
    } else {
        monthSelect.style.display = 'inline-block';
        daySelect.style.display = 'inline-block';
    }

    if (yearSelect.value && monthSelect.value) {
        monthDays(monthSelect.value, yearSelect.value);
    }
});