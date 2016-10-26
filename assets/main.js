$(function() {
    // load google maps api
    google.load('maps', '3', {
        other_params: 'key=AIzaSyBF_gAEa4OxAK4RVo_0_HQXbbou-5EJRE8&libraries=places', 
        callback: function() {
            $( "[data-autocomplete='geolocate']" ).geoAutocomplete();
        }
    });
});