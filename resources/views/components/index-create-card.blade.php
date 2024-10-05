<div class="col-md-6 d-flex justify-content-center">
    <div class="dashboard-card create-card">
        <h2>{{ __("Create your own exercises") }}</h2>
        <p class="dashboard-intro-text px-4">{{ __("Whether it'd be a school education programme, a fun test or a quiz, we got you covered! Simply follow our criteria to create an exercise for your friends or acquaintances!") }}</p>
        <div class="card-bottom">
            <a href="{{ url('/exercises/create') }}" class="btn card-btn margin-px-4-responsive margin-mx-4-responsive">{{ __("Create Exercise") }} &rarr;</a>
            <img src="{{ asset('images/index-page/create-card-pic.png') }}" alt="Create illustration" class="card-icon">
        </div>
    </div>
</div>