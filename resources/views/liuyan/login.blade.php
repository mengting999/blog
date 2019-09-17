<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>登录</title>
</head>
<body>
	<h2>login</h2>
	<form action="{{url('liuyan/do_login')}}" method="post">
	<input type="text" name="name">
	<input type="password" name="password">
	<a href="{{url('/liuyan/wechat_login')}}">微信登录</a>
	<input type="submit" value="提交">
	</form>
</body>
</html>