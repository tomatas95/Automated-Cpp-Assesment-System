@if (session()->has('message'))
<div x-data="{show: true}" x-init="setTimeout(() => show = false, 3000)" x-show="show" 
    class="flash-message flash-message-success position-fixed text-white py-3">
    <p class="flash-message-paragraph">
        <i class="fas fa-check-circle success-icon fa-lg"></i> {{ session('message') }}
    </p>
</div>
@endif

@if (session()->has('error'))
<div x-data="{show: true}" x-init="setTimeout(() => show = false, 3000)" x-show="show" 
    class="flash-message alert alert-danger position-fixed text-white py-3">
    <p class="flash-message-paragraph">
        <i class="fas fa-exclamation-circle error-icon fa-lg"></i> {{ session('error') }}
    </p>
</div>
@endif

<style>
.flash-message {
    left: 50%;
    transform: translateX(-50%);
    padding: 1rem 2rem;
    width: auto;
    max-width: 80%;
    z-index: 999;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.flash-message-success {
    background-color: #D7FFBD;
}

.flash-message-paragraph {
    color: #3C4859;
    font-size: 1rem;
    margin: 0;
}

.error-icon, .success-icon {
    margin-right: 0.5rem;
}

@media (max-width: 768px) {
    .flash-message {
        padding: 1rem;
        max-width: 90%;
        font-size: 0.9rem;
    }

    .error-icon, .success-icon {
        font-size: 1.2rem;
    }
}

@media (max-width: 480px) {
    .flash-message {
        padding: 0.75rem;
        max-width: 95%;
        font-size: 0.85rem;
    }

    .error-icon, .success-icon {
        font-size: 1rem;
    }
}
</style>
