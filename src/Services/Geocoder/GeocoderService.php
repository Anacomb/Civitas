<?php

namespace App\Services\Geocoder;

use App\Entity\Commune;
use App\Repository\CommuneRepository;
use Geocoder\Query\GeocodeQuery;
use Geocoder\Query\ReverseQuery;
use Http\Adapter\Guzzle6\Client as HttpClient;
use Geocoder\Provider\Mapbox\Mapbox;
use Geocoder\StatefulGeocoder;

class GeocoderService{

	private $httpClient;
	private $provider;
	private $geocoder;
    private $communeRepository;

	function __construct(
        CommuneRepository $communeRepository
    ){
		$this->httpClient = new HttpClient();
    	$this->provider = new Mapbox($this->httpClient, 'pk.eyJ1IjoiYW5hY29tYiIsImEiOiJjazltdG82d2EwMnp5M21scGc1cWdtOGM3In0.WbSl0RvM9KcZkU3C4EDrug');
    	$this->geocoder = new StatefulGeocoder($this->provider, 'fr');
        $this->communeRepository = $communeRepository;
    }

    function getCoordinateFromAdress(string $adress) : array
    {
    	$result = $this->geocoder->geocodeQuery(GeocodeQuery::create($adress));
    	return [
            $result->first()->getCoordinates()->getLatitude(),
            $result->first()->getCoordinates()->getLongitude()
        ];
    }

    function getAdressFromCoordinate(string $lat, string $lng) : string
    {
    	$result = $this->geocoder->reverseQuery(ReverseQuery::fromCoordinates($lat, $lng));
    	return $result->first()->getFormattedAddress();
    }

    function getNomCommuneFromAdress(string $adress) : ?string
    {
        $result = $this->geocoder->geocodeQuery(GeocodeQuery::create($adress));
        $nom_commune = $result->first()->getLocality();
        if($nom_commune) return $nom_commune;
        else return null;
    }

    function getEntityCommuneFromName(string $nom) : ?Commune
    {
        $query = $this->communeRepository->findCommuneByName($nom);
        if(!empty($query)) return $query[0];
        else return null;
    }
}