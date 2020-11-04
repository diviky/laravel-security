@component('mail::message')

<p>Hi {{ $row->name }} ({{ $row->username }}),</p>

<p> Two Factor Authentication is successfully ENABLED to your account.</p>


<p>Alternatively, you can use the code <b>{{ $secret }}</b> </p>

<center><img src="{{ $url }}" alt=""></center>

<p>You must set up your Google Authenticator app before continuing. You will be unable to login otherwise</p>

<p>
    For android App
    <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" target="_blank">
        Download </a>
    | For Ios App <a href="https://itunes.apple.com/us/app/google-authenticator/id388497605?mt=8" target="_blank">
        Download </a>
</p>

<p>Please check the same and revert back in case of any queries</p>
<p>Please do not reply to this mail as this is an auto-generated email.</p>

@endcomponent