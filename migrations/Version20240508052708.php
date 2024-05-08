<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240508052708 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create contacts table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE "contacts" (
          id VARCHAR(255) NOT NULL,
          phone VARCHAR(255) NOT NULL,
          name VARCHAR(255) NOT NULL,
          PRIMARY KEY(id)
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE "contacts"');
    }
}
