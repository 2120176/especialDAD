<!DOCTYPE html>
<html>
<head>
    <title>Welcome To Sueca Online!</title>
</head>
<body>
<h2>Welcome To Sueca Online {{$user['name']}} !</h2>
<br/>
Your registered email adress is:  {{$user['email']}} , Please click on the link below to verify your email account
<br/>
{{--<a href="{{url('user/verify', $user->verifyUser->token)}}">Verify Email</a>--}}
<a href="{{url("/#/verifyUser/$token")}}">Verify Email</a>
</body>
</html>
