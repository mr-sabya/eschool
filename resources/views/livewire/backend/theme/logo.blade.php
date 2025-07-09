@if(Route::is('admin.auth.*'))
<a href="{{ route('home') }}" wire:navigate class="logo">
    <img src="{{ url('assets/backend/images/logo-light.png') }}" height="24" alt="logo-img">
</a>
@else
<div>
    <a href="{{ route('home') }}" wire:navigate class="logo logo-dark">
        <span class="logo-sm">
            <img src="{{ url('assets/backend/images/logo-sm-dark.png') }}" alt="logo-sm-dark" height="26">
        </span>
        <span class="logo-lg">
            <img src="{{ url('assets/backend/images/logo-dark.png') }}" alt="logo-dark" height="24">
        </span>
    </a>

    <a href="{{ route('home') }}" wire:navigate class="logo logo-light">
        <span class="logo-sm">
            <img src="{{ url('assets/backend/images/logo-sm-light.png') }}" alt="logo-sm-light" height="26">
        </span>
        <span class="logo-lg">
            <img src="{{ url('assets/backend/images/logo-light.png') }}" alt="logo-light" height="24">
        </span>
    </a>
</div>
@endif