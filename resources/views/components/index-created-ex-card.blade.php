<div class="col-md-6 d-flex justify-content-center">
    <div class="dashboard-card create-ex-card">
        <h2>{{ __("View your created exercises") }}</h2>
        <p class="dashboard-intro-text px-4">{{ __("Head over to created exercises page to view all of your created exercises thus far! We display all the exercise data you need to know including participant count and further customization and in depth analysis such as editing the exercise or looking at the submittions more in depth.") }}</p>
        <div class="card-bottom">
            <a href="{{ url('/exercises/manage') }}" class="btn card-btn margin-px-4-responsive margin-mx-4-responsive">{{ __("View Created Exercises") }} &rarr;</a>
            <img src="{{ asset('images/index-page/view-created-ex-pic.png') }}" alt="View created exercises illustration" class="card-icon card-img">
        </div>
    </div>
</div>