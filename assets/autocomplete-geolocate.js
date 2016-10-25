/**
 * https://developers.google.com/maps/documentation/javascript/examples/places-autocomplete-addressform
 */

var GEOLOCATE = (function() {
    var autocomplete;

    var init = function() {
        var elms = document.getElementsByClassName( "autocomplete" );

        for(var i = 0; i < elms.length; i++) {
            var gelocate = elms[i].getAttribute('data-autocomplete');

            if (gelocate === "geolocate") {
                var type = elms[i].getAttribute('data-type');

                autocomplete = new google.maps.places.Autocomplete(
                    (elms[i]), { 
                        types: [type] 
                    }
                );

                autocomplete.addListener('place_changed', fillInAddress);
            }
        }
    };

    var fillInAddress = function() {
        var place = autocomplete.getPlace();

        var lat = place.geometry.location.lat();
        var lng = place.geometry.location.lng();

        console.log(lat, lng);
    };

    return {
        init: init
    };
}());

$.fn.geoAutocomplete = function(options) {
    var settings = $.extend({
       // plugin options
       key: 'AIzaSyBF_gAEa4OxAK4RVo_0_HQXbbou-5EJRE8'
    }, options );

    google.load('maps', '3', {
        other_params: 'key=' + settings.key + '&libraries=places', 
        callback: function() {
            GEOLOCATE.init();
        }
    });

    return this.each(function() {
        var elm = $(this);

        var componentForm = {
            street_number: 'long_name',
            route: 'long_name',
            locality: 'long_name',
            sublocality_level_1: 'long_name',
            administrative_area_level_1: 'long_name',
            country: 'short_name',
            postal_code: 'long_name'
        };




//            autocomplete.addListener('place_changed', function() {});




//            changePlace();

        function changePlace() {
            var elm = $( "#google-map-api-autocomplete" ),
                url = elm.data('url');
            var place = autocomplete.getPlace();
            var country = '';
            var address = {};

            $.each(place.address_components, function(index, value) {
                var item = $(this)[index];
                var addressType = item.types[0];

                if (componentForm[addressType]) {
                    var val = item[componentForm[addressType]];

                    address[addressType] = val;

//                        if (addressType === 'country') {
//                            country = place.address_components[i]['long_name'];
//                        }
                }
            });

            address['place_id'] = place.place_id;
            address['latitude'] = place.geometry.location.lat();
            address['longitude'] = place.geometry.location.lng();

            // save address to db
            saveObject(url, address);

//                console.log(place);
//                console.log(address);

//                var label = address.locality;

//                if (address.sublocality_level_1) {
//                    label += ' - ' + address.sublocality_level_1;
//                }
//
//                if (country) {
//                    label += ', ' + country;
//                }

//                input.hide();
//                input.after('<div class="well well-sm">' + label + '<span class="fa fa-spinner fa-pulse btn btn-sm btn-link pull-right loader"></span></div>');

        }

        // TODO: need to implement geolocation (navigator.geolocation)
        function geolocate() {
            if (navigator.geolocation) {
              navigator.geolocation.getCurrentPosition(function(position) {
                var geolocation = {
                  lat: position.coords.latitude,
                  lng: position.coords.longitude
                };
                var circle = new google.maps.Circle({
                  center: geolocation,
                  radius: position.coords.accuracy
                });
                autocomplete.setBounds(circle.getBounds());
              });
            }
        }

        function saveObject(url, address) {
            $.nette.ajax({
                url: url,
                type: 'POST',
                data: {
                    address : address
                },
                cache: false,
                error: function(jqXHR, status, error, settings){
//                        input.before('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Error when Ajax request</div>');
//                        googleMapsApiInputEmpty();
                },
                success: function(payload, status, jqXHR, settings){
                    console.log(payload.addressId);

//                        input.closest('.google-map-api-autocomplete').find('.well').find('.loader').remove();
//                        input.closest('.google-map-api-autocomplete').find('.well').append('<a href="#" class="btn btn-sm btn-link pull-right google-map-api-autocomplete-close"><span class="fa fa-times-circle"></span></a>');
//                        $('#' + input.data('input')).val(response.addressId);
                }
            });
        }

        function googleMapsApiInputEmpty() {
            $('#google-map-api-autocomplete').val('').show();
            $('#' + $('#google-map-api-autocomplete').data('input')).val('');
            $('.google-map-api-autocomplete .well').remove();
        }

//            $('.google-map-api-autocomplete').delegate('.google-map-api-autocomplete-close', 'click', function() {
//                googleMapsApiInputEmpty();
//
//                return false;
//            });
   });
};


$(function() {
    $( "[data-autocomplete='geolocate']" ).geoAutocomplete();
});