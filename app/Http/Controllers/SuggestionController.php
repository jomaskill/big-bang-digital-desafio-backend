<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class SuggestionController extends Controller
{
    const WHEATER_KEY = '68498a7cb59f9f4159eea49914df49d6';
    const SPOTIFY_KEY = 'Bearer BQCM-1Lf5FNtDeoXhGsJNTskDsI-k8mT7d3CHQzecll6hVjSkATcYQmu_HlBYJCu8L3zAXuqeJ9KvzUQABkncml9BWbmgrZRFA2z2NQU9xaMESI46-N7KXKXiSHAPIem9LRYkcXnv_TnCz3udb8zaQJ7rVYsatzLGw6gh5kdu0p-9lPt11tmZZ7Vj5EGbakrcAkPHNZFloSDko1ENtRK7GEdpih4CoxL-Xsf0GJcdTUhHCFabcr4W61ZdaIzKHESlEDZaF3vaiQ6KlaCpQ';
    const URI_WEATHER = 'api.openweathermap.org/data/2.5/weather?';
    const URI_SPOTIFY = 'https://api.spotify.com/v1/recommendations?';

    private $client;

    public function __construct()
    {
        $this->client = new Client;
    }

    public function index(Request $request)
    {
        if($request->has('latitude') || $request->has('longitude'))
            $validatedRequest = $this->validate($request, $this->validationCoordinates());
        else
            $validatedRequest = $this->validate($request, $this->validationCity());

        $temp = $this->getWeatherData($validatedRequest);

        return $this->getPlaylist($temp);
    }

    public function getWeatherData($validatedRequest) : int
    {
        if (isset($validatedRequest['city'])){
            try {
                $response = $this->client->request('GET', self::URI_WEATHER.'q='.$validatedRequest['city'].'&appid='.self::WHEATER_KEY.'&units=metric');
            } catch (\Throwable $error){
                throw new \Error('Invalid City');
            }
        }
        else{
            try {
                $response = $this->client->request('GET', self::URI_WEATHER.'lat='.$validatedRequest['latitude'].'&lon='.$validatedRequest['longitude'].'&appid='.self::WHEATER_KEY.'&units=metric');
            } catch (\Throwable $error){
                throw new \Error('Invalid Coordinates');
            }
        }

        $response = json_decode($response->getBody());

        return (int) $response->main->temp;
    }

    public function getPlaylist(int $temp) : array
    {
        $genre = $this->setGenre($temp);
        $size = 20;

        $response = $this->client->request(
            'GET',
            self::URI_SPOTIFY.'limit='.$size.'&seed_genres='.$genre,
            [
                'headers' => [
                    'Accept'     => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => self::SPOTIFY_KEY
                ]
            ]
        )->getBody();

        $response = json_decode($response);

        $response = array_map(function($item) {
            return $item->name;
        }, $response->tracks);

        return $response;
    }

    public function setGenre(int $temp): string
    {
        $musicGenre = '';

        if ($temp > 30) {
            $musicGenre = 'party';
        }
        if ($temp >= 15 && $temp <= 30) {
            $musicGenre = 'pop';
        }
        if ($temp >= 10 && $temp <= 14) {
            $musicGenre = 'rock';
        }
        if ($temp < 10) {
            $musicGenre = 'classical';
        }

        return $musicGenre;
    }
    protected function validationCity()
    {
        return [
            'city' => 'required|string'
        ];
    }

    protected function validationCoordinates()
    {
        return [
            'latitude' => ['required','regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'longitude' => ['required', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/']
        ];
    }

}
