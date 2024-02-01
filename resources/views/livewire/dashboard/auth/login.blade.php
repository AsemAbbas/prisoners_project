@section('title')
    تسجيل دخول | فجر الحرية
@endsection
@section('style')
    <style>
        body {
            height: 100%;
            overflow: hidden;
            margin: 0; /* Ensure no default margins */
        }

        /* Add this style to set full-cover background image */
        .auth-container {
            position: relative; /* Ensure relative positioning for absolute elements */
            min-height: 100vh; /* Set minimum height to cover the full viewport */
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden; /* Hide overflow to prevent scrolling */
            background-image: url('{{ asset("assets/images/login_bg.jpg") }}');
            background-size: cover;
            background-position: center;
        }

        /* Pseudo-element to create the overlay */
        .auth-container::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /*background-color: rgba(15, 15, 15, 0.5); !* Adjust opacity here *!*/
            z-index: 0; /* Place the overlay above the background image */
        }

        /* Rest of your existing styles... */

        /* Ensure the content appears above the overlay */
        .card-body {
            position: relative;
            z-index: 2; /* Place the content above the overlay */
        }
    </style>
@endsection
<div class="auth-container">
    <div class="container mx-auto align-self-center">
        <div class="row">
            <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-8 col-12 d-flex flex-column align-self-center mx-auto">
                <div class="card mt-3 mb-3">
                    <div class="card-body">
                        @if(session()->has('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <form wire:submit.prevent="login">
                            <div class="row">
                                <div class="col-md-12 mb-3 text-center">
                                    <img src="{{ asset('assets/images/logo.webp') }}" alt="شعار" width="200">
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">البريد الإلكتروني</label>
                                        <input type="email" class="form-control" wire:model="email">
                                        @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">كلمة المرور</label>
                                        <input type="password" wire:model="password" class="form-control">
                                        @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                @if(session()->has('error'))
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <div class="alert alert-danger">
                                                {{ session('error') }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-12">
                                    <div class="my-2">
                                        <button class="btn btn-outline-primary w-100">تسجيل دخول</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
