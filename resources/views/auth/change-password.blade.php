@extends('speed-admin::layouts.layout')

@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">

                @component('speed-admin::components.validation_errors')
                @endcomponent

                <form action="{{ route('admin.change-password') }}" method="post">
                    @csrf()

                    <div class="form-group">
                        <label for="old_password">{{ __('Old password') }}</label>
                        <input type="password" class="form-control" name="old_password" id="old_password"
                            value="{{ old('old_password') }}">
                    </div>

                    <div class="form-group">
                        <label for="new_password">{{ __('New password') }}</label>
                        <input type="password" class="form-control" name="new_password" id="new_password"
                            value="{{ old('new_password') }}">
                    </div>

                    <div class="form-group">
                        <label
                            for="new_password_confirmation">{{ __('Confirm new password') }}</label>
                        <input type="password" class="form-control" name="new_password_confirmation"
                            id="new_password_confirmation" value="{{ old('new_password_confirmation') }}">
                    </div>

                    <button type="submit"
                        class="btn btn-primary">{{ __('Change password') }}</button>

                </form>

            </div>
        </div>
    </div>
</div>
@endsection
