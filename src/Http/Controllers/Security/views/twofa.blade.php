@include('partials.menu.security')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Two Factor Authentication</h3>
    </div>

    <div class="card-body">

        <div class="text-center">
            <p>Set up your two factor authentication by scanning the barcode below.</p>
            <p>Alternatively, you can use the code <b>{{ $secret }}</b></p>

            {!! $qrcode !!}

            <p>You must set up your Google Authenticator app before continuing. You will be unable to login otherwise</p>

            <p>For android App <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2"
                    target="_blank"> Download </a> | For Ios App <a href="https://itunes.apple.com/us/app/google-authenticator/id388497605?mt=8"
                    target="_blank">
                    Download </a></p>


            @if ($enabled)
            <form method="POST" action="{{ url('security/2fa') }}" data-reload="true" role="ksubmit">
                @csrf
                <input type="hidden" name="secret" value="{{ $secret }}">
                <input type="hidden" name="task" value="disable">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Current Password</span>
                    </div>
                   <input type="password" class="form-control" name="password" value="" required />
                 </div>
                <div class="form-group mb-0">
                    <button type="submit" class="btn btn-danger btn-block"> Disable Security </button>
                </div>
            </form>
            @else
            <form method="POST" action="{{ url('security/2fa') }}" role="ksubmit">
                @csrf
                <input type="hidden" name="secret" value="{{ $secret }}">
                <input type="hidden" name="task" value="enable">

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Enter OTP From App</span>
                    </div>
                    <input type="text" class="form-control" maxlength="6" name="code" value="" required />
                    <div class="input-group-append">
                        <button type="submit" data-task="verify" class="btn btn-primary btn-block"> Verify OTP </button>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Current Password</span>
                    </div>
                   <input type="password" class="form-control" name="password" value="" required />
                 </div>
                <div class="form-group mb-1">
                    <button type="submit" data-task="enable" class="btn btn-primary btn-block"> Enable Security </button>
                    <p class="mt-1">Please verify your otp before enabling security</p>
                </div>
            </form>
            @endif
        </div>

    </div>
</div>
