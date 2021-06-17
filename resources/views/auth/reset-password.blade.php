@extends('speed-admin::layouts.auth')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card-group">
                <div class="card p-4">
                    <div class="card-body">
                        @component('speed-admin::components.lang_selector')
                        @endcomponent
                        <br>
                        @component('speed-admin::components.validation_errors')
                        @endcomponent
                        <form method="post" action="{{ route('admin.password.update') }}">
                            @csrf()

                            <input type="hidden" name="token" value="{{$token}}">

                            <h1>Reset Password</h1>
                            <p class="text-muted">Enter your new password twice to set new password</p>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend"><span class="input-group-text">
                                        <svg class="c-icon">
                                            <use
                                                xlink:href="{{ asset(config('speed-admin.speed_admin_assets_path').'coreui3.4.0/vendors/@coreui/icons/svg/free.svg#cil-user') }}">
                                            </use>
                                        </svg></span></div>
                                <input class="form-control" name="email" type="text" placeholder="Email">
                            </div>
                            <div class="input-group mb-4">
                                <div class="input-group-prepend"><span class="input-group-text">
                                        <svg class="c-icon">
                                            <use
                                                xlink:href="{{ asset(config('speed-admin.speed_admin_assets_path').'coreui3.4.0/vendors/@coreui/icons/svg/free.svg#cil-lock-locked') }}">
                                            </use>
                                        </svg></span></div>
                                <input class="form-control" name="password" type="password" placeholder="{{__('Password')}}">
                            </div>
                            <div class="input-group mb-4">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <svg class="c-icon">
                                            <use
                                                xlink:href="{{ asset(config('speed-admin.speed_admin_assets_path').'coreui3.4.0/vendors/@coreui/icons/svg/free.svg#cil-lock-locked') }}">
                                            </use>
                                        </svg>
                                    </span>
                                </div>
                                <input class="form-control" name="password_confirmation" type="password" placeholder="{{__('Password Confirmation')}}">
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-primary px-4" type="submit">Submit</button>
                                    <a class="btn btn-outline-secondary px-4" href="{{route('admin.login')}}" >Back to Login</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection