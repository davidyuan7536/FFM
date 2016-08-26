function maps() {
    var mapDefault = new google.maps.LatLng(54.521081, 15.292969);

    function getButtons(map) {

        var container = $('<div class="map-buttons"></div>');

        var buttonLocal = $('<div class="map-button">Local</div>').css({
            borderRight: 'none'
        }).appendTo(container);

        buttonLocal.click(function () {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    var latLng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                    map.setCenter(latLng);
                    map.setZoom(12);
                });
            }
        });

        var buttonGlobal = $('<div class="map-button">Global</div>').appendTo(container);

        buttonGlobal.click(function () {
            map.setCenter(mapDefault);
            map.setZoom(2);
        });

        return container[0];
    }

    var src = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAATCAYAAACZZ43PAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAATVJREFUeNpi/KgsxYAFiAKxKxDzQPlPgXg3EP9CV8iCxpcD4nYgjsJi6Ecg7oZiuEGMSC6wBOItQCzEgB8cBGJ/qIEMTFBBRSDeSIRmELAH4kUwDsyAdqi/iQV+QBwIMwCkMQSLou9AvBWIz+IwJB9mgAcQM6NJggLJCoh9gNgEiOuxGGADxGwgAySwSIKi7AISvw+LGpClEkzY4hZLePDj8MYvkAE3sUiYAXE1EHMCsTQQz8eRLl6D0gFI0SukVEcsWAbE0UzQ0O4gUTPI22XI6QCUPE+RYEAHNH/ADQCZGATEL4jQvAk5WpmQJJ5CDfmFR/M1II5DFmBCU3AciHNxaP6InIlwGQACs6AYGfwF4gggvoOumAmHbSBXHEHig/y8A5tCRhwlEiw1HoN6Kw6XIoAAAwBovTooRESMawAAAABJRU5ErkJggg==";

    var image = new google.maps.MarkerImage(
        src,
        null,
        new google.maps.Point(0, 0),
        new google.maps.Point(8, 19),
        new google.maps.Size(16, 19)
    );

    function setMarker(position, nmPlaceUrl) {
        if (!this.marker) {
            this.marker = new google.maps.Marker({
                icon: image,
                map: this.map
            });

            google.maps.event.addListener(this.marker, 'click', function () {
                window.open(nmPlaceUrl, '_blank');
            });
        }

        this.marker.setPosition(position);
    }

    return {
        init: function (el, options) {
            var settings = $.extend({
                scrollwheel: false,
                zoom: 3,
                center: mapDefault,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                panControl: false,
                zoomControl: true,
                zoomControlOptions: {
                    position: google.maps.ControlPosition.LEFT_TOP
                },
                mapTypeControl: true,
                mapTypeControlOptions: {
                    position: google.maps.ControlPosition.TOP_RIGHT
                },
                scaleControl: false,
                streetViewControl: false,
                overviewMapControl: false
            }, options);

            var map = new google.maps.Map(el.get(0), settings);

            if (!settings['readonly']) {
                map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(getButtons(map));
            }

            return {
                map: map,
                places: [],
                setMarker: function (lat, lng, nmPlaceUrl) {
                    var latlng = new google.maps.LatLng(lat, lng);
                    setMarker.call(this, latlng, nmPlaceUrl);
                },
                clearPlaces: function () {
                    $.each(this.places, function (i, place) {
                        place.setMap(null);
                    });
                    this.places = [];
                },
                addPlaces: function (places) {
                    var self = this;
                    $.each(places, function (i, place) {
                        var position = new google.maps.LatLng(place['place_lat'], place['place_lng']);
                        var marker = new google.maps.Marker({
                            optimized: false,
                            icon: image,
                            animation: google.maps.Animation.DROP,
                            position: position,
                            map: self.map
                        });
                        google.maps.event.addListener(marker, 'click', function () {
                            window.location = 'places/' + place['place_uuid'];
                        });
                        self.places.push(marker);
                    });
                }
            }
        }
    }
}