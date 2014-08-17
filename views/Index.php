<!DOCTYPE html>
<html>
<head>
	<title>OnWheels</title>
	
	<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" />
	<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic-ext,latin-ext,cyrillic" />
	<link rel="stylesheet" type="text/css" href="/static/Quark.css" />
	<link rel="stylesheet" type="text/css" href="/static/main.css" />
	
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=geometry&sensor=false"></script>
	<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="/static/Quark.js"></script>
	<script type="text/javascript" src="/static/map.js"></script>
	<script type="text/javascript" src="/static/main.js"></script>
	
	<script type="text/javascript">
	$(function () {
		<?php
		use \Quark\Quark;

		$user = Quark::User();

		if ($user == null) echo 'App.User.showLogin();';
		else echo '
			App.User.Data = {user:', json_encode($user), ',achievements:', sizeof($achievements), '};
			App.User.showProfile();
		';

		echo 'App.Markers.Load(', json_encode($places), ');';
		?>

		App.Map.Zoom(14, true);
		App.Map.Center({
			lat: 46.9842927059675,
			lng: 28.852930068969727
		});
	});
	</script>
</head>

<body>
<div id="map"></div>

<img class="logo" src="/static/img/logo.png" alt="logo" />
<div class="logo"></div>

<div class="block" id="user"></div>

<div id="user-template-login" class="template">
	<nav class="nav-bg-violet">
		<a onClick="App.User.Login();" class="q-control q-button violet" title="Войти с помощью Facebook">&#xf09a;</a>
	</nav>
</div>

<div id="user-template-profile" class="template">
	<nav class="nav-bg-dark">
		<a onClick="Quark._serialize('#place form');" title="Редактировать профиль">&#xf044;</a>
		<a onClick="App.User.Logout();" title="Выход">&#xf08b;</a>
	</nav>
	<div class="content q-form">
		<h4><b>{user.first_name} {user.last_name}</b></h4>
		<!--{user.distance}-->56.29 км<br>
		5 из 8<!--{user.achievementCount} из {achievements}--> <a href="/user/profile/{user._id}" title="Профиль" style="color: rgb(127, 127, 127);">&#xf091;</a><!-- &#xf1b9;-->
	</div>
</div>

<div class="block" id="place"></div>

<div class="block" id="menu">
	<nav>
		<div id="menu-authorized"></div>
		<a title="Легенда" onClick="App.Menu.Legend();">&#xf128;</a>
		<a title="Join us on GitHub">&#xf09b;</a>
	</nav>
	<div id="menu-content"></div>
</div>

<div id="user-template-menu" class="template">
	<a title="Создать место/заезд" onClick="App.Place.Create();">&#xf067;</a>
	<!--<a title="Настройки" onClick="app.menu.Settings();">&#xf085;</a>-->
</div>

<div id="menu-template-settings" class="template">
	<div class="content q-form" style="width: 250px;">
		<a class="close-button" onClick="App.Entities.menu_main.close();">&#xf00d;</a>

		<div class="q-control-group">
			<input class="q-control" name="legend" type="checkbox" value="{settings.legend}" />
			<label class="q-label" for="settings-form-legend" />
			Показывать легенду
			</label>
		</div>
		<div class="q-control-group">
			<input class="q-control" name="near" type="checkbox" value="{settings.near}" />
			<label class="q-label" for="settings-form-near" />
			Выбирать только ближайшие места
			</label>
		</div>
		<br>

		<button class="q-control q-button violet" type="button">&#xf00c;</button>
	</div>
</div>

<div id="menu-template-legend" class="template">
	<div class="content-transparent q-form">
		<div class="row">
			<img src="/static/img/pin_race.png" alt="pin_type" />
			Заезд
		</div>
		<div class="row">
			<img src="/static/img/pin_rnr.png" alt="pin_type" />
			Отдых
		</div>
		<div class="row">
			<img src="/static/img/pin_source.png" alt="pin_type" />
			Источник
		</div>
		<div class="row">
			<img src="/static/img/pin_studio.png" alt="pin_type" />
			Мастерская
		</div>
		<div class="row">
			<img src="/static/img/pin_store.png" alt="pin_type" />
			Магазин/прокат
		</div>
	</div>
</div>

<div id="place-template-create" class="template">
	<nav></nav>
	<form class="content q-form" action="/place/create" method="POST">
		<a class="close-button" onClick="App.Entities.place.close();">&#xf00d;</a>

		<p class="q-header-mini">Создание заезда</p>
		<p class="q-notice error hidden">Ошибка сервера</p>
		<p class="q-notice success hidden">Заезд создан успешно</p>
		<p class="q-notice pure">Нарисуйте маршрут заезда, указывая точки на карте.</p>

		<input class="q-control" style="width: 150px;" name="date" type="datetime-local" placeholder="Дата и время проведения" onchange="App.validator.Rule(this, Quark.Validator.Required, 'Обязательное'); App.validator.Rule(this, Quark.Validator.Date, 'Дата в формате ГГГГ-ММ-ДД ЧЧ:ММ:СС');" />
		<textarea class="q-control" style="width: 300px; height: 50px;" name="description" placeholder="Описание"></textarea>
		<br>

		<button class="q-control q-button violet" type="submit">&#xf00c;</button>
	</form>
</div>


<div id="place-template-race" class="template">
	<nav>
		<a title="Редактировать">&#xf044;</a>
		<a title="Удалить" onClick="App.Entities.place.remove('{_id}');">&#xf00d;</a>
	</nav>
	<div class="content q-form">
		<a class="close-button" onClick="App.Entities.place.close();">&#xf00d;</a>

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
		<!--<div class="row"></div>
		<div class="row">
			<span class="icon">&#xf11d;</span>
			<span id="race-start">Lorem Street 1</span>
		</div>
		<div class="row">
			<span class="icon">&#xf024;</span>
			<span id="race-finish">Foo Bar street 12</span>
		</div>-->
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

<div id="pin-template-race" class="template">
	<div class="pin-item">
		<div class="pin-name">{name}</div>
		<!--<div class="pin-description">{description}</div>-->
	</div>
</div>

<div id="pin-template-rnr" class="template">
	<div class="pin-item">
		<div class="pin-name">{name}</div>
		<!--<div class="pin-description">{description}</div>-->
	</div>
</div>

<div id="pin-template-source" class="template">
	<div class="pin-item">
		<div class="pin-name">{name}</div>
		<!--<div class="pin-description">{description}</div>-->
	</div>
</div>

<div id="pin-template-store" class="template">
	<div class="pin-item">
		<div class="pin-name">{name}</div>
		<!--<div class="pin-description">{description}</div>-->
	</div>
</div>

<div id="pin-template-studio" class="template">
	<div class="pin-item">
		<div class="pin-name">{name}</div>
		<!--<div class="pin-description">{description}</div>-->
	</div>
</div>

</body>
</html>