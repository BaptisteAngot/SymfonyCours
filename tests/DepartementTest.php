<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DepartementTest extends WebTestCase
{
    public function GetAllDepartments() {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/login_check',
            array(),
            array(),
            array("CONTENT_TYPE" => "application/json"),
            json_encode(array(
                'username' => 'admin@admin.fr',
                'password' => 'myPassword'
            ))
        );
        $data = json_decode($client->getResponse()->getContent(), true);
        $token = $data['token'];

        $client->request('GET', '/api/v2/json/departments', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode(
          $client->getResponse()->getContent(),
          true
        );
        $this->assertContains('Normandie',$data[0]);
    }

}
