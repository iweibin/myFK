{% extends 'layout.php' %}
{% block content %}


<form action="./register" method="post">
	<input type="text" name="username" > <br>
	<input type="text" name="name" > <br>
	<input type="submit" name="sub" value="注册">
</form>

{% endblock %}