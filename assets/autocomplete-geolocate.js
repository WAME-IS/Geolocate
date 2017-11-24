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


    /**
     * Each Geo autocomplete inputs
     */
    return this.each(function() {
        var $this = $(this);
        
        var type = $this.data('type');
        
        var autocomplete = new google.maps.places.Autocomplete($this[0], {types: [type]});
        
        autocomplete.addListener('place_changed', changePlace);
        
        init($this);
        
        function init (elm) {
            elm.addClass( 'gma-plugin-initialized' );
        }

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


        /**
         * Send to API
         *
         * @param input
         * @param url
         * @param address
         */
        function saveObject(input, url, address) {
            createDiv(input);

            input.closest('form').find('[type="submit"]').addClass('disabled');

            $.nette.ajax({
                url: url,
                type: 'POST',
                data: {
                    address : address
                },
                cache: false,
                success: function(payload, status, jqXHR, settings) {
                    selectValue(input, payload);
                },
                error: function(error) {
                    if (error.responseJSON.error) {
                        googleMapsApiWarning(input, error);
                    }

                    googleMapsApiInputEmpty(input);
                },
                complete: function() {
                    input.closest('form').find('[type="submit"]').removeClass('disabled');
                }
            });
        }


        /**
         * Create DIV with loader
         *
         * @param input
         */
        function createDiv(input)
        {
            var wrapper = input.closest('.google-map-api-autocomplete');

            input.hide();
            wrapper.append('<div class="well well-sm"><span class="gmaa-title">' + input.val() + '</span><span class="fa fa-refresh fa-spin btn-link pull-right gmaa-loader"></span></div>');
        }


        /**
         * Select value to input
         *
         * @param input
         * @param data
         */
        function selectValue(input, data)
        {
            input.val(data.id);

            var wrapper = input.closest('.google-map-api-autocomplete');

            wrapper.find('.gmaa-title').text(data.title);
            wrapper.find('.gmaa-loader').remove();
            $('<a href="#" class="btn-sm btn-link pull-right gmaa-close"><span class="fa fa-times-circle"></span></a>').appendTo(wrapper.find('.well'));
        }


        /**
         * Empty input and DIV
         *
         * @param input
         */
        function googleMapsApiInputEmpty(input) {
           input.val('').show();

           input.closest('.google-map-api-autocomplete').find('.well').remove();
        }


        /**
         * Create warning
         *
         * @param input
         * @param error
         */
        function googleMapsApiWarning(input, error)
        {
            var wrapper = input.closest('.google-map-api-autocomplete');

            wrapper.addClass('has-error');
            $('#' + input.attr('data-lfv-message-id')).addClass('help-block text-danger').text(error.responseJSON.error).show();
        }


        /**
         * Remove button click
         */
        $('.google-map-api-autocomplete').delegate('.gmaa-close', 'click', function() {
            var input = $(this).closest('.google-map-api-autocomplete').find('input');
            
            googleMapsApiInputEmpty(input);
            input.trigger( 'input' ).trigger( 'focus' );

           return false;
        });


        /**
         * Set value on load
         */
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