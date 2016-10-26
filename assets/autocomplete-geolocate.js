/**
 * https://developers.google.com/maps/documentation/javascript/examples/places-autocomplete-addressform
 */

$.fn.geoAutocomplete = function(options) {
    var settings = $.extend({
        componentForm: {
            street_number: 'long_name',
            route: 'long_name',
            locality: 'long_name',
            sublocality_level_1: 'long_name',
            administrative_area_level_1: 'long_name',
            country: 'short_name',
            postal_code: 'long_name'
        }
    }, options);
    
    /**
     * Save to data
     * 
     * @param {string} name
     * @param {mixed} value
     */
    function saveToData(name, value) {
        if(name) {
            $el = $("[name='" + name + "']");
            if($el.length) {
                $el.val(value);
            }
        }
    }
    
    return this.each(function() {
        var $this = $(this);
        
        var type = $this.data('type');
        
        var autocomplete = new google.maps.places.Autocomplete($this[0], {types: [type]});
        
        autocomplete.addListener('place_changed', changePlace);

        function changePlace() {
            var url = $this.data('url');
            var place = autocomplete.getPlace();
            var country = '';
            var address = {};

            $.each(place.address_components, function(index, value) {
                var addressType = value.types[0];

                if (settings.componentForm[addressType]) {
                    var val = value[settings.componentForm[addressType]];

                    address[addressType] = val;

//                        if (addressType === 'country') {
//                            country = place.address_components[i]['long_name'];
//                        }
                }
            });

            address['place_id'] = place.place_id;
            address['latitude'] = place.geometry.location.lat();
            address['longitude'] = place.geometry.location.lng();

            saveToData($this.data('el-placeId'), address['place_id']);
            saveToData($this.data('el-latitude'), address['latitude']);
            saveToData($this.data('el-longitude'), address['longitude']);

            // save address to db
            if($this.data('url')) {
                saveObject(url, address);
            }
            
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

//        function googleMapsApiInputEmpty() {
//            $('#google-map-api-autocomplete').val('').show();
//            $('#' + $('#google-map-api-autocomplete').data('input')).val('');
//            $('.google-map-api-autocomplete .well').remove();
//        }

//            $('.google-map-api-autocomplete').delegate('.google-map-api-autocomplete-close', 'click', function() {
//                googleMapsApiInputEmpty();
//
//                return false;
//            });
   });
};