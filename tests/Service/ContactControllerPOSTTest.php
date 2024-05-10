<?php

namespace App\Tests\Service;

use App\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactControllerPOSTTest extends WebTestCase
{
    const NAME_SIMONYTE = 'Simonyte';
    const PHONE = '+37066611111';
    const API_V_1_CONTACT_URI = '/api/v1/contact';
    const TOO_LONG_PHONE = '111111111111111111111111111111111111111111111';
    const API_LOGIN_URI = '/api/login';
    const EXISTING_USERNAME = 'user7';
    const EXISTING_USER_PASSWORD = 'secret123';

    public function testAuthorizationNotSet(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::API_V_1_CONTACT_URI);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testCreateContactCorrectBody(): void
    {
        $body = [
            'name' => self::NAME_SIMONYTE,
            'phone' => self::PHONE,
        ];
        $client = $this->makePostRequestWithBody($body);
        $response = $client->getResponse();
        $contact = json_decode($response->getContent(), true);

        $persistedContact = $this->getEntityManager()->getRepository(Contact::class)->find($contact['id']);

        $this->assertEquals(self::NAME_SIMONYTE, $persistedContact->getName());
        $this->assertEquals(self::PHONE, $persistedContact->getPhone());
        $this->assertNotNull($persistedContact->getId());
        $this->assertResponseIsSuccessful();
    }

    public function testCreateContactMissingName(): void
    {
        $body = [
            'name' => '',
            'phone' => self::PHONE,
        ];
        $client = $this->makePostRequestWithBody($body);
        $response = $client->getResponse();

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertEquals('{"name":"This value should not be blank."}', $response->getContent());
    }

    public function testCreateContactMissingPhone(): void
    {
        $body = [
            'name' => self::NAME_SIMONYTE,
        ];
        $client = $this->makePostRequestWithBody($body);
        $response = $client->getResponse();

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertEquals('{"phone":"This value should not be blank."}', $response->getContent());
    }

    public function testCreateContactTooShortPhone(): void
    {
        $body = [
            'name' => self::NAME_SIMONYTE,
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
            'name' => self::NAME_SIMONYTE,
            'phone' => self::TOO_LONG_PHONE,
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
            Request::METHOD_POST,
            self::API_LOGIN_URI,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => self::EXISTING_USERNAME,
                'password' => self::EXISTING_USER_PASSWORD,
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
            Request::METHOD_POST,
            self::API_V_1_CONTACT_URI,
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
