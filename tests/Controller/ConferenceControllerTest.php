<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ConferenceControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Give your feedback');
    }

    public function testConferencePage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        echo $client->getResponse();
        $this->assertCount(1, $crawler->filter('h4'));

        //$client->clickLink('View');
        $client->click($crawler->filter('h4 + p a')->link());

        $this->assertPageTitleContains('Chelyabinsk');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Chelyabinsk 2019');
        //$this->assertSelectorExists('div:contains("There are 1 comments")');
    }
}