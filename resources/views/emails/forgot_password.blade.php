@component('mail::message')
# Hello.

パスワード変更のリクエストを頂きました。以下のリンクにクリックし、パスワードをご変更ください。

OTP Code: <b>{{ $otp_code }}</b>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
