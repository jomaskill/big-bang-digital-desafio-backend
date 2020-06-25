<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Lang;
use Tests\TestCase;

class SuggestionControllerTest extends TestCase
{
    const URI = '/suggestion';

    public function testResponse()
    {
        $data = [
            'city'=>'belo horizonte'
        ];

        $response = $this->json('GET', self::URI, $data);

        $size = 20;
        $response->assertJsonCount($size);
    }

    public function testCityRequired()
    {
        $data = [
            'city'=>''
        ];

        $this->assertInvalidation($data, 'required');
    }

    public function testCityString()
    {
        $data = [
            'city'=> 000
        ];

        $this->assertInvalidation($data, 'string');
    }

    public function testCoordinatesRequired()
    {
        $data = [
            'latitude' => '',
            'longitude' => ''
        ];

        $this->assertInvalidation($data, 'required');
    }

    public function testCoordinatesRegex()
    {
        $data = [
            'latitude' => 'asfasfasfsafasfasfsafw',
            'longitude' => '321321d21d2rasfsafsfafas'
        ];

        $this->assertInvalidation($data, 'regex');
    }

    protected function assertInvalidation(array $data, string $rule, array $ruleParams = [])
    {
        $fields = array_keys($data);
        $response = $this->json('GET', self::URI, $data);
        $this->assertInvalidationField($response, $rule, $fields, $ruleParams);
    }

    protected function assertInvalidationField($response, string $rule, array $fields, array $ruleParams = [])
    {
        $response->assertStatus(422)
            ->assertJsonValidationErrors($fields);

        foreach ($fields as $field) {
            $fieldName = str_replace("_", " ", $field);
            $response->assertJsonFragment([Lang::get("validation.{$rule}", ['attribute' => $fieldName] + $ruleParams)]);
        }
    }

}
