<head>
    @vite(['resources/sass/partials/_search.styles.scss'])
</head>

<div class="container search-container">
    <form action="{{ $action }}" id="searchForm" class="mx-auto search-responsiveness search-exercises">
        <div class="input-group position-relative">
            <div class="input-container position-relative flex-grow-1">
                <input id="search-element" type="text" name="search" class="form-control border search-input" placeholder="{{ $placeholder }}" aria-label="Search" aria-describedby="search-addon" value="{{ request()->get('search') }}">
                <span id="clearSearchInput" class="clear-btn">&times;</span>
                <div id="searchContainer" class="recent-searches mt-2">
                    <p class="text-muted">{{ __("Recent Searches") }} <span id="clearBtn" class="clear-all float-right">{{ __("Clear All") }}</span></p>
                    <ul id="recentSearchList"></ul>
                </div>
            </div>
        <div class="input-group-append ">
            <button type="submit" class="btn btn-search bg-light">
                <i class="fa fa-search"></i>
            </button>
        </div>
        </div>
    </form>
</div>
