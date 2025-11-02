<div class="mx-3">

    {{-- Session Messages --}}
    @if (session()->has('success'))
    <div class="alert alert-success text-center">
        {{ session('success') }}
    </div>
    @elseif (session()->has('error'))
    <div class="alert alert-danger text-center">
        {{ session('error') }}
    </div>
    @endif

    <form class="form-horizontal mt-3 " action="" wire:submit.prevent="login">

        <div class="form-group mb-3">
            <div class="col-12">
                <input class="form-control" type="text" name="username" wire:model="username" placeholder="Username">
                @error('username') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="form-group mb-3">
            <div class="col-12">
                <input class="form-control" type="password" name="password" wire:model="password" placeholder="Password">
                @error('password') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="form-group">
            <div class="col-12">
                <div class="checkbox checkbox-primary">
                    <input id="checkbox-signup" type="checkbox" wire:model="remember">
                    <label for="checkbox-signup" class="text-color">
                        Remember me
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group text-center mt-3">
            <div class="col-12">

                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="login"
                    class="btn btn-primary btn-block btn-lg waves-effect waves-light w-100">
                    <span wire:loading.remove wire:target="login">Login</span>
                    <span wire:loading wire:target="login">Login.....</span>
                </button>
            </div>
        </div>

        <div class="form-group row mt-4 mb-0">
            <div class="col-sm-12 text-center">
                <a href="auth-recoverpw.html" class="text-color">
                    <i class="mdi mdi-lock me-1"></i> Forgot your password?</a>
            </div>

        </div>
    </form>
    <!-- <hr>
    <div class="row g-2">
        <div class="col-lg-6">
            <button class="btn btn-primary btn-block waves-effect waves-light w-100" wire:click="superAdminLogin">Superadmin Login</button>
        </div>
        <div class="col-lg-6">
            <button class="btn btn-primary btn-block waves-effect waves-light w-100" wire:click="adminLogin">Admin Login</button>
        </div>
        <div class="col-lg-6">
            <button class="btn btn-primary btn-block waves-effect waves-light w-100" wire:click="companyLogin">Company Login</button>
        </div>
        <div class="col-lg-6">
            <button class="btn btn-primary btn-block waves-effect waves-light w-100" wire:click="employeeLogin">Employee Login</button>
        </div>
    </div> -->

</div>