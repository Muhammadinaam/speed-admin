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

                        @if(session()->has('status'))
                            <p class="alert alert-success">{{session('status')}}</p>
                        @endif

                        <form method="post" action="{{ route('admin.login') }}">
                            <input type="hidden" name="redirect_after_login" value="{{request()->redirect_after_login}}">
                            @csrf()
                            <h1>{{__('Login')}}</h1>
                            <p class="text-muted">{{__('Sign In to your account')}}</p>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend"><span class="input-group-text">
                                        <svg class="c-icon">
                                            <use
                                                xlink:href="{{ asset(config('speed-admin.speed_admin_assets_path').'coreui3.4.0/vendors/@coreui/icons/svg/free.svg#cil-user') }}">
                                            </use>
                                        </svg></span></div>
                                <input class="form-control" name="email" type="text" placeholder="{{__('Email')}}">
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
                            <div class="row">
                                <div class="col-6">
                                    <button class="btn btn-primary px-4" type="submit">{{__('Login')}}</button>
                                </div>
                                <div class="col-6 text-right">
                                    <a class="btn btn-link px-0"
                                        href="{{ route('admin.forgot-password-form') }}">{{__('Forgot password?')}}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card text-white bg-primary pt-5 d-md-down-none" style="width:44%">
                    <div class="card-body text-center">
                        <div>
                            <h1 class="mt-5">{{config('speed-admin.title')}}</h1>
                            <p>{{__('Welcome back')}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection