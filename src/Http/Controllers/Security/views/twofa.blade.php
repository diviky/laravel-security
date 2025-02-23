<div class="card">
    <div class="card-header">
        <h3 class="card-title">Two Factor Authentication</h3>
    </div>

    <div class="card-body">

        <div class="text-center">
            <p>Set up your two factor authentication by scanning the barcode below.</p>
            <p>Alternatively, you can use the code <b>{{ $secret }}</b></p>

            {!! $qrcode !!}

            <p class="mt-4">
                You must set up your Google Authenticator app before continuing.
                You will be unable to login otherwise
            </p>

            <p>For android App <a
                    href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2"
                    target="_blank"> Download </a> | For Ios App <a
                    href="https://itunes.apple.com/us/app/google-authenticator/id388497605?mt=8" target="_blank">
                    Download </a></p>

            @if ($enabled)
                <form method="POST" action="{{ url('security/2fa') }}" data-reload="true" easysubmit>
                    @csrf
                    <input type="hidden" name="secret" value="{{ $secret }}">
                    <input type="hidden" name="task" value="disable">
                    <div class="input-group mb-3">
                        <span class="input-group-text">Current Password</span>
                        <input type="password" class="form-control" name="password" value="" required />
                    </div>
                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-danger btn-block"> Disable Security </button>
                    </div>
                </form>
            @else
                <form method="POST" action="{{ url('security/2fa') }}" easysubmit>
                    @csrf
                    <input type="hidden" name="secret" value="{{ $secret }}">
                    <input type="hidden" name="task" value="enable">

                    <div class="input-group mb-3">
                        <span class="input-group-text">Enter OTP from app</span>
                        <input type="text" class="form-control" maxlength="6" name="code" value=""
                            required />

                        <button type="submit" data-task="verify" class="btn btn-primary">
                            Verify OTP
                        </button>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text">Current Password</span>
                        <input type="password" class="form-control" name="password" value="" />
                        <button type="submit" data-task="enable" class="btn btn-primary">
                            Enable Security
                        </button>
                    </div>
                    <p class="mt-1">Please verify your otp before enabling security</p>
                </form>
            @endif
        </div>
    </div>
</div>
