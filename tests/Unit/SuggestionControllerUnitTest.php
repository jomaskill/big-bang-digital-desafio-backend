<?php

namespace Tests\Unit;

use App\Http\Controllers\SuggestionController;
use PHPUnit\Framework\TestCase;

class SuggestionControllerUnitTest extends TestCase
{
    const WHEATER_KEY = '68498a7cb59f9f4159eea49914df49d6';
    const SPOTIFY_KEY = 'Bearer BQCM-1Lf5FNtDeoXhGsJNTskDsI-k8mT7d3CHQzecll6hVjSkATcYQmu_HlBYJCu8L3zAXuqeJ9KvzUQABkncml9BWbmgrZRFA2z2NQU9xaMESI46-N7KXKXiSHAPIem9LRYkcXnv_TnCz3udb8zaQJ7rVYsatzLGw6gh5kdu0p-9lPt11tmZZ7Vj5EGbakrcAkPHNZFloSDko1ENtRK7GEdpih4CoxL-Xsf0GJcdTUhHCFabcr4W61ZdaIzKHESlEDZaF3vaiQ6KlaCpQ';
    const URI_WEATHER = 'api.openweathermap.org/data/2.5/weather?';
    const URI_SPOTIFY = 'https://api.spotify.com/v1/recommendations?';

    private $obj;

    public function testSetGenre()
    {
        $obj = $this->object();

        $response = $obj->setGenre(9);

        self::assertEquals($response, 'classical');

        $response = $obj->setGenre(10);

        self::assertEquals($response, 'rock');

        $response = $obj->setGenre(14);

        self::assertEquals($response, 'rock');

        $response = $obj->setGenre(15);

        self::assertEquals($response, 'pop');

        $response = $obj->setGenre(30);

        self::assertEquals($response, 'pop');

        $response = $obj->setGenre(31);

        self::assertEquals($response, 'party');
    }

    public function testConstants()
    {
        $model = $this->obj;

        $this->assertEquals($model::WHEATER_KEY, self::WHEATER_KEY);
        $this->assertEquals($model::SPOTIFY_KEY , self::SPOTIFY_KEY);
        $this->assertEquals($model::URI_WEATHER , self::URI_WEATHER);
        $this->assertEquals($model::URI_SPOTIFY , self::URI_SPOTIFY);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = $this->model();
    }

    protected function model()
    {
        return SuggestionController::class;
    }

    protected function object()
    {
        return new SuggestionController;
    }
}
