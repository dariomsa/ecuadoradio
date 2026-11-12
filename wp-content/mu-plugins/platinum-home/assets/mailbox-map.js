(() => {
	const mapElement = document.getElementById('mapid');
	if (!mapElement || typeof window.L === 'undefined') {
		return;
	}

	const coordinates = [-0.19522382291000612, -78.48015608894865];
	const map = window.L.map(mapElement, {
		scrollWheelZoom: false,
	}).setView(coordinates, 16);

	window.L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
		maxZoom: 19,
	}).addTo(map);

	window.L.marker(coordinates).addTo(map);
})();
