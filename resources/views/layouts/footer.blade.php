<footer class="footer-section mb-4">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <h2 id="contact" class="footer-heading pb-5 pt-5">{{ __("Additional Information") }}</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 text-center d-flex flex-column align-items-center">
                <div class="footer-brand">
                    <img src="{{ asset('images/web-logo.png') }}" alt="iCodeNET Logo" class="footer-logo mb-3">
                    <span class="footer-title text-secondary">{{ $shortCodes['company_name'] }}</span>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <h3 class="footer-subheading">{{ __("Important Links") }}</h3>
                <ul class="footer-links">
                    <li><a href="/">{{ __("News") }}</a></li>
                    @guest
                    <li><a href="#about">{{ __("About") }}</a></li>
                    <li><a href="/login">{{ __("Login") }}</a></li>
                    <li><a href="/register">{{ __("Register") }}</a></li>
                    @endguest
                    @auth
                    <li><a href="/index">{{ __("Dashboard") }}</a></li>
                    <li><a href="/create">{{ __("Create Exercise") }}</a></li>
                    <li><a href="/users/my-profile">{{ __("My Profile") }}</a></li>
                    @endauth
                </ul>
            </div>
            <div class="col-md-3 text-center">
                <h3 class="footer-subheading">{{ __("Contact Us") }}</h3>
                <p>{{ __($shortCodes['email']) }}</p>
                <p>&copy; {{ __($shortCodes['copyright']) }}.</p>
            </div>
            <div class="col-md-3 text-center">
                <h3 class="footer-subheading">{{ __("Residence") }}</h3>
                <p>{{ __($shortCodes['residence']) }} &reg;</p>
            </div>
        </div>
    </div>
</footer>
