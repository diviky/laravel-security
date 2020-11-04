@component('mail::message')
# Holla!

Your {{ env('app.name') }} account logged in from a new device.

> **Account:** {{ $account->email }} **{{ $account->username }}**<br>
> **Time:** {{ $history->created_at }}<br>
> **IP Address:** {{ $history->ip }}<br>
> **Browser:** {{ $history->browser }}<br>
> **Location:** {{ $history->location }} {{ $history->region }} {{ $history->city }}<br>
> **Device:** {{ $history->device }} {{ $history->brand }} {{ $history->os }}

If this was you, you can ignore this alert. If you suspect any suspicious activity on your account, please change your password.

Regards,<br>{{ env('app.name') }}
@endcomponent
