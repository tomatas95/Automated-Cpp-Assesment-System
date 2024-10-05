
<div class="row">
    <div class="col-md-6 d-flex align-items-center justify-content-center position-relative">
        <img src="{{ asset('images/welcome-page/laptop-pic.png') }}" class="welcome-header-img" alt="A laptop illustration" class="img-fluid">
    </div>
    <div class="col-md-6 d-flex align-items-center justify-content-center flex-column text-container text-center">
        <h1 class="intro-headings">{{ __('A new digital way of learning programming') }}</h1>
        <p class="intro-text">{{ __('Learn programming with our built-in cloud compiler! Create exercises, add hints, test each participant’s submission with our automatic testing tool!') }}</p>
        @guest
        <a class="btn btn-lg welcome-btns sign-up-btn" href="{{ url('/users/register') }}">{{ __('Sign Up') }}</a>            
        @endguest
    </div>
</div>
<div class="row">
    <div class="col-md-6 d-flex align-items-center justify-content-center flex-column text-container text-center">
        <h2 class="mid-headings">{{ __('Find your favorite exercises!') }}</h2>
        <p class="mid-text">{{ __("It's widely known and acknowledged by many programming experts and other people that without practical problem solving, you won't learn how to be a true programmer. Therefore, this website will offer you many problem solving exercises that you can pick and learn along the way!") }}
        </p>
    </div>
    <div class="col-md-6 d-flex align-items-center justify-content-center position-relative">
        <img src="{{ asset('images/welcome-page/study-pic.png') }}" class="welcome-header-img" alt="Person studying programming illustration" class="img-fluid">
    </div>
</div>