/**
 * https://developers.google.com/maps/documentation/javascript/examples/places-autocomplete-addressform
 */
$.fn.geoAutocomplete = function(options) {
    /** @type {Object} */
    var settings = $.extend({
        componentForm: {
            street_number: 'long_name',
            route: 'long_name',
            locality: 'long_name',
            sublocality_level_1: 'long_name',
            administrative_area_level_1: 'long_name',
            administrative_area_level_2: 'long_name',
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
        if (name) {
            $el = $("[name='" + name + "']");

            if ($el.length) {
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
                    
                    saveToData($this.data('el-' + addressType), val);
                }
            });

            address['place_id'] = place.place_id;
            address['latitude'] = place.geometry.location.lat();
            address['longitude'] = place.geometry.location.lng();
            address['formatted_address'] = place.formatted_address;

            saveToData($this.data('el-place_id'), address['place_id']);
            saveToData($this.data('el-latitude'), address['latitude']);
            saveToData($this.data('el-longitude'), address['longitude']);

            // save address to db
            if ($this.data('url')) {
                saveObject($this, url, address);
            }
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


        function saveObject(input, url, address) {
            createDiv(input);

            $.nette.ajax({
                url: url,
                type: 'POST',
                data: {
                    address : address
                },
                cache: false,
                success: function(payload, status, jqXHR, settings) {
                    selectValue(input, payload);
                }
            });
        }


        function createDiv(input)
        {
            var wrapper = input.closest('.google-map-api-autocomplete');

            input.hide();
            wrapper.append('<div class="well well-sm"><span class="google-map-api-autocomplete-title">' + input.val() + '</span><span class="fa fa-refresh fa-spin btn btn-link pull-right loader"></span></div>');
        }


        function selectValue(input, data)
        {
            input.val(data.id);

            var wrapper = input.closest('.google-map-api-autocomplete');

            wrapper.find('.google-map-api-autocomplete-title').text(data.title);
            wrapper.find('.loader').remove();
            $('<a href="#" class="btn btn-sm btn-link pull-right google-map-api-autocomplete-close"><span class="fa fa-times-circle"></span></a>').appendTo(wrapper.find('.well'));
        }


        function googleMapsApiInputEmpty(input) {
           input.val('').show();

           input.closest('.google-map-api-autocomplete').find('.well').remove();
        }


        $('.google-map-api-autocomplete').delegate('.google-map-api-autocomplete-close', 'click', function() {
           googleMapsApiInputEmpty($(this).closest('.google-map-api-autocomplete').find('input'));

           return false;
        });


        function setValueOnLoad() {
            if ($this.data('id')) {
                var data = {};

                data.id = $this.data('id');
                data.title = $this.val();

                createDiv($this);
                selectValue($this, data);

                $this.removeAttr('data-id');
            }
        }

        setValueOnLoad();

   });

};