<section class="vh-lg-100 mt-5 mt-lg-0 bg-soft d-flex align-items-center">
    @section('page-title')
    Login
    @endsection
    <div class="container">
        <div class="row justify-content-center form-bg-image"
            style="background:url({{asset('backend/assets/img/illustrations/signin.svg')}})">
            <div class="col-12 d-flex align-items-center justify-content-center">
                <div class="bg-white shadow border-0 rounded border-light p-4 p-lg-5 w-100 fmxw-500">
                    <div class="text-center text-md-center mb-4 mt-md-0">
                        <h1 class="mb-0 h3">Sign in to our platform</h1>
                    </div>
                    <form class="mt-4" wire:submit.prevent="UserLogin" method="POST">
                        <div class="form-group mb-4">
                            <label for="email">Your Email</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1">
                                    <i class="fa fa-envelope fa-fw"></i>
                                </span>
                                <input type="text" class="form-control" placeholder="Email or Username"
                                    wire:model="email" autofocus required>
                            </div>
                            @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <div class="form-group mb-4">
                                <label for="password">Your Password</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon2">
                                        <i class="fa fa-lock fa-fw"></i>
                                    </span>
                                    <input type="password" placeholder="Password" class="form-control"
                                        wire:model="password" required>
                                </div>
                                @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-top mb-4">
                                <div class="form-check">
                                    <label class="form-check-label mb-0" for="remember">
                                        <input class="form-check-input" type="checkbox" id="remember" value="1"
                                            wire:model="remember">
                                        Remember me
                                    </label>
                                </div>
                                {{-- <div><a href="./forgot-password.html" class="small text-right">Lost
                                        password?</a></div> --}}
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-gray-800">
                                SIGN IN
                                <div class="spinner-border spinner-border-sm text-white" wire:loading
                                    wire:target="UserLogin">
                                </div>
                            </button>
                        </div>
                    </form>

                    <!--div class="d-flex justify-content-center align-items-center mt-4">
                        <span class="fw-normal">
                            Not registered?
                            <a href="{{route("login")}}" class="fw-bold">Create account</a>
                        </span>
                    </div-->
                </div>
            </div>
        </div>

    </div>
    @include('backend._message')
</section>