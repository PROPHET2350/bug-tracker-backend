<?php

namespace App\tests;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjectTest extends WebTestCase
{


    public function setUp(): void
    {
    }

    public function testProjectCreation()
    {
        $client = static::createClient();
        $project = [
            'id' => '1',
            'name' => 'luis fonsi'
        ];
        $client->request('POST', '/project/add', [], [], [], json_encode($project));
        $this->assertResponseIsSuccessful();
        if (json_decode($client->getResponse()->getContent())->project == $project) {
            var_dump("ok");
        } else {
            var_dump("bad");
        }
    }
}