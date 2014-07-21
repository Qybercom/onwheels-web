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
	    app.mark('source', {
	        position: {
	            lat: 46.994360231512694,
	            lng: 28.846905827522278
	        }
	    });
	
	    app.mark('rnr', {
	        position: {
	            lat: 46.98343086328941,
	            lng: 28.86157751083374
	        },
	        data: {
	            name: 'NonStop 24/24',
	            description: '"Будка"'
	        }
	    });
	
	    app.mark('race', {
	        position: {
	            lat: 46.98628349113864,
	            lng: 28.858423233032227
	        },
	        data: {
		    	_id: 'a123test',
	            author: {
	                id: 'test_mongo_id',
	                firstname: 'Саша',
	                lastname: 'Фурника',
	                nickname: 'Chief'
	            },
	            navpoints: [
	                {lat: 46.98628349113864, lng: 28.858423233032227},
	                {lat: 46.98159917272892, lng: 28.865032196044922},
	                {lat: 46.97662163467422, lng: 28.872756958007812},
	                {lat: 46.97504043737847, lng: 28.873615264892578},
	                {lat: 46.97439623248324, lng: 28.870182037353516},
	                {lat: 46.97439623248324, lng: 28.864946365356445},
	                {lat: 46.97427910348673, lng: 28.860139846801758},
	                {lat: 46.972522137753536, lng: 28.854475021362305},
	                {lat: 46.97205360380936, lng: 28.850955963134766},
	                {lat: 46.97668019663912, lng: 28.84589195251465},
	                {lat: 46.97785142246889, lng: 28.84486198425293},
	                {lat: 46.98587362966407, lng: 28.857736587524414}
	            ],
	            date: '25.06.2014 01:24',
	            participants: [
	                'test_mongo_id',
					'test_mongo_id_2'
	            ]
	        }
	    });
	});
	</script>
</head>

<body>
<div id="map"></div>

<img class="logo" src="/static/img/logo.png" alt="logo" />
<div class="logo"></div>

<div class="block" id="user">
	<nav class="nav-bg-dark">
		<a title="Редактировать профиль">&#xf044;</a>
		<a title="Выход">&#xf08b;</a>
	</nav>
	<div class="content">
		<h4 onClick="centerit();"><b>Саша Фурника</b> (Chief)</h4>
		100500 км<br>
		12 из 15 &#xf091; &#xf1b9;
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
			<a id="race-author">{author.firstname} {author.lastname}</a>
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