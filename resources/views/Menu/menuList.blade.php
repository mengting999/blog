<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>菜单列表</title>
</head>
<body>
	<h2>创建菜单</h2>
	<form action="{{url('wechat/create_menu')}}" method="post">
	@csrf
		一级菜单名称<input type="text" name="name1"><br></br>
		二级菜单名称<input type="text" name="name2"><br></br>
		菜单类型
		<select name="type" id="">
			<option value="1">click</option>
			<option value="2">view</optopn>
		</select>
		<br></br>
		事件值
		<input type="text" name="event_value"><br></br>
		<input type="submit" value="提交">

	</form>
	<h2>菜单列表</h2>
</body>
</html>