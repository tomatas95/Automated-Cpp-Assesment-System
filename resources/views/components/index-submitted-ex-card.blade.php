<div class="col-md-6 d-flex justify-content-center">
    <div class="dashboard-card submit-ex-card">
        <h2>{{ __("View your submitted exercises") }}</h2>
        <p class="dashboard-intro-text px-4">{{ __("Would it either be to analyze your submittions or take a nostalgic trip to exercises you've completed in this journey, head over to submitted exercises page to not only see sent out solutions but also recheck how many exercises you've gotten correct with the help of the automation checker!") }}</p>
        <div class="card-bottom">
            <a href="{{ url('/submissions') }}" class="btn card-btn margin-px-4-responsive margin-mx-4-responsive">{{ __("View Your Submittions") }} &rarr;</a>
            <img src="{{ asset('images/index-page/view-submitted-ex-pic.png') }}" alt="View submitted exercises illustration" class="card-icon">
        </div>
    </div>
</div>