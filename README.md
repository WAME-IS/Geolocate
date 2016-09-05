# GeolocateAutocompleteFormControl

Autocomplete vyhľadávanie v Google maps API

## Použitie

na stránke https://console.developers.google.com/apis/credentials si vytvoríme API key
na koniec v `<body>` pod všetky scripty pridáme

```
<script src="https://maps.googleapis.com/maps/api/js?key=<YOUR_API_KEY>&amp;libraries=places&amp;callback=initGoogleMapiApiAutocomplete" async="" defer=""></script>
```

### Vo formulári

```
$form->addGeolocateAutocomplete('city', _('City'))
     ->setAttribute('placeholder', _('Begin typing the name of the city'))
	 ->setRequired(_('Please enter city'));
```

Môže vyhľadávať niekoľko typov ktoré nájdeme tu https://developers.google.com/places/web-service/autocomplete#place_types
v základe vyhľadáva `(regions)` tj. mestá a ich okolité časti

```
$form->addGeolocateAutocomplete('city', _('City'))
     ->setType('(cities)');
```

Po kliknutí na výsledok sa odošle AJAX dotaz na URL kde sa dáta spracujú
v základe je to `/location/address?do=createAddressFromGoogleMapApi`
ktorá overí či už existuje taký Region, City ak nie tak ich vytvorí
a potom vytvorí novú adresu do hidden input vráti ID tejto adresy

ak chceme zmeniť URL
```
$form->addGeolocateAutocomplete('city', _('City'))
     ->setType('address')
     ->setUrl('$this->getPresenter()->link(':Location:Address:create', ['do' => 'createAddressFromGoogleMapApi']);
```

#### Odkazy
https://developers.google.com/maps/articles/phpsqlsearch_v3
https://developers.google.com/places/web-service/autocomplete#example_autocomplete_requests
https://developers.google.com/maps/documentation/javascript/examples/places-autocomplete-addressform