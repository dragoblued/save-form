if($('#map').length) {
	var loc = $('#map').data('loc');
	loc = loc.split('|');
	var locations = loc[0].split(',').map(Number);
	var scale = parseFloat(loc[1]);
	ymaps.ready(function () {
		var loc = locations,
			z = scale;
		var myMap = new ymaps.Map('map', {
				center: locations,
				zoom: z
			}, {
				searchControlProvider: 'yandex#search'
			}),
			myPlacemark = new ymaps.Placemark(loc, {
				hintContent: '',
				balloonContent: ''
			}, {
				iconLayout: 'default#image',
				iconImageHref: '/img/ico/map_ballon.png',
				iconImageSize: [ 50, 70],
				// Смещение левого верхнего угла иконки относительно
				// её "ножки" (точки привязки).
				iconImageOffset: [-25, -70]
			});
		myMap.behaviors.disable('scrollZoom');

		myMap.geoObjects.add(myPlacemark);
	});
}