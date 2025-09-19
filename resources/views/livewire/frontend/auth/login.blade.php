<div class="container my-5">
    <div style="height: 65vh; width: 100%">
        <div class="row justify-content-center align-items-center h-100">
            <div class="col-md-4">
                <div class="card p-5">

                    <div class="card-body">
                        <h4 class="text-center">Student Login</h4>
                        <form wire:submit.prevent="login">
                            @csrf

                            <div class="mb-3">
                                <label for="username">{{ __('Username') }}</label>
                                <input type="username" id="username" class="form-control @error('username') is-invalid @enderror" wire:model.defer="username" required autofocus>
                                @error('username') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password">{{ __('Password') }}</label>
                                <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" wire:model.defer="password" required>
                                @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-check mb-3">
                                <input type="checkbox" id="remember" class="form-check-input" wire:model.defer="remember">
                                <label for="remember" class="form-check-label">{{ __('Remember Me') }}</label>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">{{ __('Login') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>