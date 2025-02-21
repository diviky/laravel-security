<div class="card">
    <div class="card-header">
        <h3 class="card-title"><b>Security Info</b></h3>
    </div>

    <div class="card-body">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tabajax" href="{{ url('security/logins') }}">
                    <b>Login History</b>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tabajax" href="{{ url('security/sessions') }}">
                    <b>Logged In Sessions</b>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tabajax" href="{{ url('security/2fa') }}">
                    <b>Enable 2FA</b>
                </a>
            </li>
        </ul>
        <div class="tab-content pt-5">
            <div class="tab-pane fade show active" data-tab ajax-content>

            </div>
        </div>
    </div>

</div>
