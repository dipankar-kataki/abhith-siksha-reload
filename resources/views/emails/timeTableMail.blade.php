<!DOCTYPE html>
<html>
<head>
    <title>abhith.ekodusproject.tech</title>
</head>
<body>
    <p>Hello {{$details['name']}},</p>
    <p>Abhith Siksha invite you to a scheduled Zoom Class.</p>
    <p>Subject:{{$details['subject_name']}}/ Board:{{$details['board']}}/Class:{{$details['class']}}</p>
    <p>Time:{{$details['date']}}/{{$details['time']}}</p>
    <p>Join Link:<a href="{{$details['link']}}">{{$details['link']}}</a></p>
    <p>Thank you</p>
</body>
</html>