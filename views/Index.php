<!DOCTYPE html>
<html>
<head>
	<title>OnWheels</title>
	
	<link rel="styleshret" type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" />
	<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic-ext,latin-ext,cyrillic" />
	<link rel="stylesheet" type="text/css" href="/static/quark.css" />
	<link rel="stylesheet" type="text/css" href="/static/main.css" />
	
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=geometry&sensor=false"></script>
	<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="/static/template.js"></script>
	<script type="text/javascript" src="/static/map.js"></script>
	<script type="text/javascript" src="/static/main.js"></script>
	
	<script type="text/javascript">
	$(function () {
		<?php
		use \Quark\Quark;

		$user = Quark::User();

		if ($user == null) echo 'app.user.login();';
		else echo '
			app.user.data = {user:', json_encode($user), '};
			app.user.profile();
		';

		foreach ($places as $i => $place) {
			echo '
				app.mark(\'', $place->type, '\', {
					position: ', json_encode($place->position), ',
					data: ', json_encode($place), '
				});
			';
		}
		?>
	});
	</script>
</head>

<body>
<div id="map"></div>

<img class="logo" src="/static/img/logo.png" alt="logo" />
<div class="logo"></div>

<div class="block" id="user"></div>

<div id="user-template-login">
	<nav class="nav-bg-violet">
		<a onClick="app.user.Login();" class="q-control q-button violet" title="Войти с помощью Facebook">&#xf09a;</a>
	</nav>
</div>

<div id="user-template-profile">
	<nav class="nav-bg-dark">
		<a title="Редактировать профиль">&#xf044;</a>
		<a onClick="app.user.Logout();" title="Выход">&#xf08b;</a>
	</nav>
	<div class="content">
		<h4><b>{user.first_name} {user.last_name}</b></h4>
		{user.distance} км<br>
		{user.achievements} из {achievements.count} &#xf091; &#xf1b9;
	</div>
</div>

<div class="block" id="place"></div>

<div class="block" id="menu">
	<nav>
		<a title="Создать место/заезд" onClick="app.route.create();">&#xf067;</a>
		<a title="Настройки">&#xf085;</a>
		<a title="Легенда">&#xf128;</a>
		<a title="Join us on GitHub">&#xf09b;</a>
	</nav>
</div>

<div id="place-template-create">
	<nav></nav>
	<form class="content" style="width: 325px;" action="/place/save/{place.id}" method="POST">
		<p class="q-header-mini">Создание заезда</p>
		<p class="q-notice pure">Нарисуйте маршрут заезда, указывая точки на карте.</p>
		
		<div class="q-control-group">
			<input class="q-control width-45" name="name" id="place-form-name" type="text" placeholder="Название" value="{place.name}" />
			<label class="q-label right width-40" for="place-form-name" />
				При указании названия, оно отобразиться рядом с маркером
			</label>
		</div>
		
		<div class="q-control-group">
			<input class="q-control width-35" name="date" type="date" placeholder="Дата проведения" value="{place.date}" />
			<input class="q-control width-20" name="time" type="time" placeholder="Время" value="{place.time}" />
		</div>
		<br>
		
		<button class="q-control q-button violet" type="button">&#xf00c;</button>
	</form>
</div>


<div id="place-template-race">
	<nav>
		<a title="Редактировать">&#xf044;</a>
		<a title="Удалить" onClick="app.route.clear('{_id}');">&#xf00d;</a>
	</nav>
	<div class="content">
		<div class="row">
			<img src="/static/img/pin_{type}.png" alt="pin_type" />
			<b>Заезд</b>
		</div>
		<div class="row"></div>
		<div class="row">
			<span class="icon">&#xf007;</span>
			<a id="race-author">{author.first_name} {author.last_name}</a>
		</div>
		<div class="row">
			<span class="icon">&#xf133;</span>
			<span id="race-date">{date}</span>
		</div>
		<div class="row"></div>
		<div class="row">
			<span class="icon">&#xf11d;</span>
			<span id="race-start">Lorem Street 1</span>
		</div>
		<div class="row">
			<span class="icon">&#xf024;</span>
			<span id="race-finish">Foo Bar street 12</span>
		</div>
		<div class="row"></div>
		<div class="row">
			<span class="icon">&#xf07e;</span>
			<span id="race-length">{length}</span> км
		</div>
		<div class="row">
			<span class="icon">&#xf0c0;</span>
			<span id="race-participants">{members}</span>
		</div>
	</div>
</div>

<div id="pin-template-rnr">
	<div class="pin-rnr">
		<div class="pin-rnr-name">{name}</div>
		<div class="pin-rnr-description">{description}</div>
	</div>
</div>

</body>
</html>