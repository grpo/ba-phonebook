<?php

namespace App\Tests\Service;

use App\Service\RequestHeaderParser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RequestHeaderParserTest extends WebTestCase
{
    public function testAuthorizationNotSet(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/v1/contact');

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testAuthorizationSet(): void
    {
//        $this->markTestSkipped('Not sure how properly set headers for WebTestCase crawler');

        $requestHeaderParser = new RequestHeaderParser();

        $client = static::createClient();
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MTUzMTU3NzcsImV4cCI6MTcxNTM1MTc3Nywicm9sZXMiOltdLCJ1c2VybmFtZSI6InVzZXIzIn0.R8CjmdJn4T7qGsQ0eY7znur3ZYdCvXdUlm-SXhPPlOxIksGSjV6ZHo7PXqYwDSolX7Ddni46UqlAQSHcEsroIxC07DK1aFXgl0rmhbmkNHBdyE8xlcuzxpxX6wNggjlGaCcgABhE_Yr2vZalvxUm9eQHv-J758_4-3ZDeKZM47GvB3rz6u_IKMs1vnJF5j6z-h1GhY0T8tyrI-HJKxhmehkU8VrRKdaf-Cmx7-zmcER3mF0hfuRICWgtyE23El0X5teIgoVL6ReT4wJo12ijbZGrANpxJ6I-9oq8vNLAwhBfxOZXODJpL0k3kIMGEqLyyGgkfbMHbGwBu9y7cR07Fw';
        $crawler = $client->request(
            Request::METHOD_GET,
            '/api/v1/contact',
             [
                 'headers' => [
                     'AUTHORIZATION' => 'Bearer ' . $token,
                 ]
             ]
        );

        // get Response
        // pass Response object to requestHeaderParser
        // assert if token is same


        $this->assertResponseIsSuccessful();
    }
}
