@extends('layouts.single')

@section('content')

<div class="card card-small">
    <div class="card-body p-6">
        <div class="card-title">{{ __('One Time Password') }}</div>

        @if (session('status'))
        <div class="alert alert-{{ session('status') }}">
            {{ session('message') }}
        </div>
        @endif
        <p class="text-muted">Open <b>Google Authenticator App</b> and type OTP below.</p>

        <form method="POST" action="{{ url('2fa/remember') }}" role="ksubmit">
            @csrf

            <div class="form-group">
                <input name="one_time_password" maxlength="6" type="number" class="form-control" value="" required>
            </div>

            <div class="form-group hide">
                <label class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="remember" value="1">
                    <span class="custom-control-label">Remember me on this device</span>
                </label>
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary btn-block">
                    {{ __('Verify OTP') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection