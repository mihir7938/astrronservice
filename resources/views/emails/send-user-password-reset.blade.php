<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
    <h2>Password Reset</h2>
    <div>Hello <span style="color: #111C25;font-weight: 700;">{{$result['name']}},</span></div>
    <div>To reset your password, complete this form:</div>
    <a href="{{$result['reset_password_link']}}">{{$result['reset_password_link']}}</a>
</body>