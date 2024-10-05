<div class="container-fluid dashboard-intro">
    <div class="row align-items-center px-3">
        <div class="col-md-6 text-center text-md-left">
            <div class="d-flex align-items-center justify-content-center">
                <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/profile-pic.png') }}" alt="Profile Picture" class="profile-pic">
                <div>
                    <span class="dashboard-greeting">{{ __("Welcome") }} @auth<strong class="dashboard-username">{{ auth()->user()->name }}</strong></span>@endauth
                    <p class="dashboard-subheading margin-left-responsive">{{ __("to your Member Dashboard") }}</p>
                </div>
            </div>
            <p class="dashboard-intro-text padding-x-responsive padding-m-responsive">{{ __("Welcome to iCodeNET, Scroll down to see variety of programming exercises written by not only our fellow volunteers, but us as well! We share sentiment to improve and adapt, so our exercises are made with aim to not only help people improve their programming skills but their own individual skills, which are becoming a major skill in the world of digitalization.") }}
            </p>
        </div>
        <div class="col-md-6 text-center d-none d-md-block">
            <img src="{{ asset('images/index-page/reaching-goal-pic.png') }}" alt="Person holding a flag illustration" class="img-fluid intro-image">
        </div>
    </div>
</div>