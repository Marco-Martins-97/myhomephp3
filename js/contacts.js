function loadMap(){
    const officeLoc = [41.547187, -8.433896];
    
    const map = L.map('map').setView(officeLoc, 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{ attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'}).addTo(map);
    L.marker(officeLoc).addTo(map).bindPopup('My Home');//.openPopup()

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition((position) => {
            const userLoc = [position.coords.latitude, position.coords.longitude];
            
            L.marker(userLoc, { icon: L.icon({
                iconUrl: 'https://leafletjs.com/examples/custom-icons/leaf-red.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41]
            })}).addTo(map).bindPopup("User")//.openPopup();
            
            const routingUrl = `https://router.project-osrm.org/route/v1/driving/${userLoc[1]},${userLoc[0]};${officeLoc[1]},${officeLoc[0]}?overview=full&geometries=geojson`;

            fetch(routingUrl)
                .then(response => response.json())
                .then(data => {
                    const routeCoords = data.routes[0].geometry.coordinates.map(coord => [coord[1], coord[0]]);
                    L.polyline(routeCoords, { color: 'blue' }).addTo(map);
            });
        });
    }
}

$(document).ready(function(){
    loadMap();
});