@props(['exercise'])
<div class="col-md-6 d-flex justify-content-center">
    <div class="dashboard-card edit-card">
        <h2>{{ __("Tell us more about yourself...") }}</h2>
        <p class="dashboard-intro-text px-4">{{ __("Wish to express yourself more to iCodeNET community? Head over to your profile to add more information about yourself and share your own experiences and lifelong goals.") }}
        </p>
        <div class="card-bottom">
            <a href="{{ url('/users/my-profile') }}" class="btn card-btn margin-px-4-responsive margin-mx-4-responsive margin-my-4-responsive">{{ __("View Profile") }} &rarr;</a>
            <img src="{{ asset('images/index-page/edit-card-pic.png') }}" alt="Edit illustration" class="card-icon">
        </div>
    </div>
</div>