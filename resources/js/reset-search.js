document.addEventListener('DOMContentLoaded', function() {
    const maxSearchDisplay = 4;

    const searchInput = document.getElementById("search-element");
    const clearBtn = document.getElementById("clearSearchInput")
    const recentSearchList = document.getElementById('recentSearchList');
    const recentSearchesContainer = document.getElementById("searchContainer");
    const clearAllBtn = document.getElementById("clearBtn");
    const form = document.getElementById("searchForm");
    const resetBtn = document.getElementById('resetSearchFilter');

    let recentSearches = JSON.parse(localStorage.getItem('recentSearches')) || [];

    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.value = '';
            }
            const currentUrl = resetBtn.getAttribute('data-url');
            window.location.href = currentUrl;
        });
    }

    function showSearchHistory() {
        recentSearchList.innerHTML = '';

        if (recentSearches.length > 0) {
            recentSearches.forEach(search => {
                const searchListItem = document.createElement('li');
                searchListItem.textContent = search;

                searchListItem.addEventListener('click', function() {
                    searchInput.value = search;
                    recentSearchesContainer.style.display = 'none';
                    form.submit();
                });
                recentSearchList.appendChild(searchListItem);
            });
        }
    }

    showSearchHistory();

    searchInput.addEventListener('focus', function() {
        if (recentSearches.length > 0) {
            recentSearchesContainer.style.display = 'block';
        }
    });

    searchInput.addEventListener('blur', function() {
        setTimeout(() => {
            recentSearchesContainer.style.display = 'none';
        }, 100);
    });

    clearAllBtn.addEventListener('click', function() {
        recentSearches = [];
        localStorage.setItem('recentSearches', JSON.stringify(recentSearches));

        showSearchHistory();
        recentSearchesContainer.style.display = 'none';
    });

    if (searchInput && recentSearchesContainer) {
        searchInput.addEventListener('focus', function() {
            recentSearchesContainer.classList.add('show');
        });

        document.addEventListener('click', function(event) {
            if (!recentSearchesContainer.contains(event.target) && event.target !== searchInput) {
                recentSearchesContainer.classList.remove('show');
            }
        });
    }

    if (clearBtn && searchInput) {
        toggleClearButton();

        searchInput.addEventListener('input', function() {
            toggleClearButton();
        });

        clearBtn.addEventListener('click', function() {
            searchInput.value = '';
            toggleClearButton();
        });

        function toggleClearButton() {
            if (searchInput.value.length > 0) {
                clearBtn.style.display = 'block';
            } else {
                clearBtn.style.display = 'none';
            }
        }
    }

    form.addEventListener('submit', function(event) {
        event.preventDefault();
        const searchValue = searchInput.value.trim();

        if (searchValue) {
            if (!recentSearches.includes(searchValue)) {
                if (recentSearches.length >= maxSearchDisplay) {
                    recentSearches.pop();
                }
                recentSearches.unshift(searchValue);
                localStorage.setItem('recentSearches', JSON.stringify(recentSearches));
            }
            form.submit();
        }
    });
});