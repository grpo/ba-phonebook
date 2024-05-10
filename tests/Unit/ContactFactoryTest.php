<?php

namespace App\Tests\Unit;

use App\Dto\Input\ContactInputDto;
use App\Entity\Contact;
use App\Entity\User;
use App\Factory\ContactFactory;
use PHPUnit\Framework\TestCase;

class ContactFactoryTest extends TestCase
{

    public function testSuccesfullContactCreation(): void
    {
        $contactDto = new ContactInputDto();
        $contactDto->setName('Jonas');
        $contactDto->setPhone('888111888');
        $user = new User();
        $user->setUsername('nickname')
            ->setPassword('secret');
        $contactFactory = new ContactFactory();

        $contact = $contactFactory->fromInputDto($contactDto, $user);

        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertEquals('Jonas', $contact->getName());
        $this->assertEquals('888111888', $contact->getPhone());
        $this->assertInstanceOf(User::class, $contact->getUser());
    }

    public function testMissingUser(): void
    {
        $this->expectException(\ArgumentCountError::class);

        $contactDto = new ContactInputDto();
        $contactDto->setName('Jonas');
        $contactDto->setPhone('888111888');
        $contactFactory = new ContactFactory();

        $contact = $contactFactory->fromInputDto($contactDto);
    }

    public function testWrongArgumentTypes(): void
    {
        $this->expectException(\TypeError::class);

        $contactFactory = new ContactFactory();

        $contact = $contactFactory->fromInputDto(null, null);
    }
}
