<?php

namespace App\Tests\Service;

use App\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ContactControllerPOSTTest extends WebTestCase
{
    public function testAuthorizationNotSet(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/v1/contact');

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testCreateContactCorrectBody(): void
    {
        $body = [
            'name' => 'Simonyte',
            'phone' => '+37066611111',
        ];
        $client = $this->makePostRequestWithBody($body);
        $response = $client->getResponse();
        $contact = json_decode($response->getContent(), true);

        $persistedContact = $this->getEntityManager()->getRepository(Contact::class)->find($contact['id']);

        $this->assertEquals('Simonyte', $persistedContact->getName());
        $this->assertEquals('+37066611111', $persistedContact->getPhone());
        $this->assertNotNull($persistedContact->getId());
        $this->assertResponseIsSuccessful();
    }

    public function testCreateContactMissingName(): void
    {
        $body = [
            'name' => '',
            'phone' => '+37066611111',
        ];
        $client = $this->makePostRequestWithBody($body);
        $response = $client->getResponse();

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertEquals('{"name":"This value should not be blank."}', $response->getContent());
    }

    public function testCreateContactMissingPhone(): void
    {
        $body = [
            'name' => 'Simonyte',
        ];
        $client = $this->makePostRequestWithBody($body);
        $response = $client->getResponse();

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertEquals('{"phone":"This value should not be blank."}', $response->getContent());
    }

    public function testCreateContactTooShortPhone(): void
    {
        $body = [
            'name' => 'Simonyte',
            'phone' => '',
        ];
        $client = $this->makePostRequestWithBody($body);
        $response = $client->getResponse();

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertEquals(
            '{"phone":"This value is too short. It should have 8 characters or more."}',
            $response->getContent()
        );
    }

    public function testCreateContactPhoneTooLong(): void
    {
        $body = [
            'name' => 'Simonyte',
            'phone' => '111111111111111111111111111111111111111111111',
        ];
        $client = $this->makePostRequestWithBody($body);
        $response = $client->getResponse();

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertEquals(
            '{"phone":"This value is too long. It should have 16 characters or less."}',
            $response->getContent()
        );
    }

    private function getAuthenticatedClient(): KernelBrowser
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => 'user7',
                'password' => 'secret123',
            ])
        );

        $data = json_decode(ltrim(rtrim($client->getResponse()->getContent())), true);

        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $client;
    }

    private function makePostRequestWithBody(array $body): KernelBrowser
    {
        $client = $this->getAuthenticatedClient();
        $client->request(
            'POST',
            '/api/v1/contact',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($body)
        );

        return $client;
    }

    private function getEntityManager()
    {
        return $this->getClient()->getContainer()->get('doctrine.orm.entity_manager');
    }
}
