/**
 * https://developers.google.com/maps/documentation/javascript/examples/places-autocomplete-addressform
 */

var googleMapsApiFields = {
    street_number: 'long_name',
    route: 'long_name',
    locality: 'long_name',
    sublocality_level_1: 'long_name',
    administrative_area_level_1: 'long_name',
    country: 'short_name',
    postal_code: 'long_name'
};

function initGoogleMapiApiAutocomplete() {
    if (document.getElementById('google-map-api-autocomplete')) {
        var type = $('#google-map-api-autocomplete').data('type');

        autocomplete = new google.maps.places.Autocomplete(
            (document.getElementById('google-map-api-autocomplete')), 
            { types: [type] }
        );

        autocomplete.addListener('place_changed', placeChanged);
    }
}

function placeChanged() {
    var input = $('#google-map-api-autocomplete');
    var url = input.data('url');
    var place = autocomplete.getPlace();
    var country = '';
    var address = {};

    for (var i = 0; i < place.address_components.length; i++) {
        var addressType = place.address_components[i].types[0];

        if (googleMapsApiFields[addressType]) {
            var val = place.address_components[i][googleMapsApiFields[addressType]];
            address[addressType] = val;
            
            if (addressType === 'country') {
                country = place.address_components[i]['long_name'];
            }
        }
    }
    
    address['place_id'] = place.place_id;
    address['latitude'] = place.geometry.location.lat();
    address['longitude'] = place.geometry.location.lng();
    
//    console.log(place);
//    console.log(address);

    var label = address.locality;
    
    if (address.sublocality_level_1) {
        label += ' - ' + address.sublocality_level_1;
    }
    
    if (country) {
        label += ', ' + country;
    }

    input.hide();
    input.after('<div class="well well-sm">' + label + '<span class="fa fa-spinner fa-pulse btn btn-sm btn-link pull-right loader"></span></div>');

    $.nette.ajax({
        url: url,
        type: 'POST',
        data: {
            address : address
        },
        cache: false,
        error: function(){
            input.before('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Error when Ajax request</div>');
            googleMapsApiInputEmpty();
        },
        success: function(response){
            input.closest('.google-map-api-autocomplete').find('.well').find('.loader').remove();
            input.closest('.google-map-api-autocomplete').find('.well').append('<a href="#" class="btn btn-sm btn-link pull-right google-map-api-autocomplete-close"><span class="fa fa-times-circle"></span></a>');
            $('#' + input.data('input')).val(response.addressId);
        }
    });
}

function googleMapsApiInputEmpty() {
    $('#google-map-api-autocomplete').val('').show();
    $('#' + $('#google-map-api-autocomplete').data('input')).val('');
    $('.google-map-api-autocomplete .well').remove();
}

$('.google-map-api-autocomplete').delegate('.google-map-api-autocomplete-close', 'click', function() {
    googleMapsApiInputEmpty();
    
    return false;
});