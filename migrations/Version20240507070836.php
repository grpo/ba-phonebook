<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240507070836 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates users table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE "users" (
          id UUID NOT NULL,
          PASSWORD VARCHAR(255) NOT NULL,
          created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
          updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('COMMENT ON COLUMN "users".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "users".created_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE "users"');
    }
}
