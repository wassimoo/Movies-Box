$(document).ready(function () {
    'use strict';

    function initialize() {
        $(".google-map").each(function (index) {

            //Taking data attribute from map wrapper
            var mapLat = parseFloat($(this).data('lat'));
            var mapLng = parseFloat($(this).data('lng'));
            var mapZoom = parseInt($(this).data('zoom'));

            //Processing wrapper data attribute to coordinate
            var mapOptions = {
                center: {
                    lat: mapLat,
                    lng: mapLng
                },
                zoom: mapZoom,
                scrollwheel: false,
                styles: [
                    {
                        elementType: 'geometry',
                        stylers: [{
                            color: '#333333'
                        }]
                    },
                    {
                        elementType: 'labels.text.stroke',
                        stylers: [{
                            color: '#333333'
                        }]
                    },
                    {
                        elementType: 'labels.text.fill',
                        stylers: [{
                            color: '#111111'
                        }]
                    },
                    {
                        featureType: 'administrative.locality',
                        elementType: 'labels.text.fill',
                        stylers: [{
                            color: '#333333'
                        }]
            },
                    {
                        featureType: 'poi',
                        elementType: 'labels.text.fill',
                        stylers: [{
                            color: '#666666'
                        }]
            },
                    {
                        featureType: 'poi.park',
                        elementType: 'geometry',
                        stylers: [{
                            color: '#263c3f'
                        }]
            },
                    {
                        featureType: 'poi.park',
                        elementType: 'labels.text.fill',
                        stylers: [{
                            color: '#6b9a76'
                        }]
            },
                    {
                        featureType: 'road',
                        elementType: 'geometry',
                        stylers: [{
                            color: '#2b2b2b'
                        }]
            },
                    {
                        featureType: 'road',
                        elementType: 'geometry.stroke',
                        stylers: [{
                            color: '#2b2b2b'
                        }]
            },
                    {
                        featureType: 'road',
                        elementType: 'labels.text.fill',
                        stylers: [{
                            color: '#666666'
                        }]
            },
                    {
                        featureType: 'road.highway',
                        elementType: 'geometry',
                        stylers: [{
                            color: '#222222'
                        }]
            },
                    {
                        featureType: 'road.highway',
                        elementType: 'geometry.stroke',
                        stylers: [{
                            color: '#666666'
                        }]
            },
                    {
                        featureType: 'road.highway',
                        elementType: 'labels.text.fill',
                        stylers: [{
                            color: '#8d7b60'
                        }]
            },
                    {
                        featureType: 'transit',
                        elementType: 'geometry',
                        stylers: [{
                            color: '#2f3948'
                        }]
            },
                    {
                        featureType: 'transit.station',
                        elementType: 'labels.text.fill',
                        stylers: [{
                            color: '#d59563'
                        }]
            },
                    {
                        featureType: 'water',
                        elementType: 'geometry',
                        stylers: [{
                            color: '#1a1a1a'
                        }]
            },
                    {
                        featureType: 'water',
                        elementType: 'labels.text.fill',
                        stylers: [{
                            color: '#1a1a1a'
                        }]
            },
                    {
                        featureType: 'water',
                        elementType: 'labels.text.stroke',
                        stylers: [{
                            color: '#17263c'
                        }]
            }
          ]
            };

            //Initiating map
            var map = new google.maps.Map(this, mapOptions);

            //Map Marker
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(mapLat, mapLng),
                map: map,
                icon: 'images/template/map-marker.png'
            });
        });
    }
    google.maps.event.addDomListener(window, 'load', initialize);
});
