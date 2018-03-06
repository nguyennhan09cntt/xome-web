/**
 * Google Map
 */
var map = $('#map');

if (map.length) {


    function initialize() {

        // Map Initial Location
        var initLatitude = 10.762622;
        var initLongitude = 106.660172;
        var zoom = 12;
        var last_infowindow = null;
        if ($('#lat').val()) {
            initLatitude = $('#lat').val();
        }
        if ($('#lng').val()) {
            initLongitude = $('#lng').val();
        }

        if ($('#search_district').val() != 0) {
            zoom = 14;
        }

        var center = new google.maps.LatLng(initLatitude, initLongitude);

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: zoom,
            center: center,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });


        var markersCluster = [];
        var bound = new google.maps.LatLngBounds();
        $.each(markers, function (index, markerObject) {
            var content = "<div class=\"infobox\"><a class=\"infobox-image\" href=\"" + markerObject.link + "\" target=\"_blank\"><img width=\"150\" height=\"150\" src=\"" + markerObject.image + "\" class=\"attachment-thumbnail size-thumbnail wp-post-image\" alt=\"interiors\" \/><\/a><div class=\"infobox-content\"><div class=\"infobox-content-title\"><a href=\"" + markerObject.link + "\" title=\"" + markerObject.title + "\" target=\"_blank\">" + markerObject.title + "<\/a><\/div><div class=\"infobox-content-body\"><div class=\"infobox-content-body-location\">" + markerObject.district + " <\/div><div class=\"infobox-content-body-area\"><span>Diện tích: <\/span><strong>" + markerObject.area + " m2<\/strong><\/div><div class=\"infobox-content-body-beds\"><span>Giá: <\/span><strong>" + markerObject.price + "<\/strong> <a class=\"pull-right product-detail-link\" href=\"" + markerObject.link + "\" target=\"_blank\"><strong>Chi tiết<\/strong><\/a><\/div><div class=\"infobox-content-phone\"><span>Phone: <\/span><strong>" + markerObject.phone + "<\/strong><\/div><\/div><\/div><\/div>";
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(markerObject.latitude, markerObject.longitude),
                //image: markerObject.image,
                icon: markerObject.image

            });
            var infowindow = new google.maps.InfoWindow({
                content: content
            });
            infowindow.setZIndex(9999);
            google.maps.event.addListener(marker, 'click', function () {
                if (last_infowindow) {
                    last_infowindow.close();
                }
                infowindow.open(map, marker);
                last_infowindow = infowindow;
            });
            markersCluster.push(marker);

            bound.extend(new google.maps.LatLng(markerObject.latitude, markerObject.longitude));

        });







        var options = {
            imagePath: '/statics/asset/default/images/m'
        };

        var markerCluster = new MarkerClusterer(map, markersCluster, options);

        if(markers.length == 1) {
            map.setZoom(zoom);
            map.panTo(new google.maps.LatLng(markers[0].latitude, markers[0].longitude));
        }else{
            map.fitBounds(bound);
        }
    }

    google.maps.event.addDomListener(window, 'load', initialize);


}
