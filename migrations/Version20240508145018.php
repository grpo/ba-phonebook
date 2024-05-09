<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240508145018 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE "contacts" (
          id UUID NOT NULL,
          user_id UUID DEFAULT NULL,
          phone VARCHAR(255) NOT NULL,
          name VARCHAR(255) NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_33401573A76ED395 ON "contacts" (user_id)');
        $this->addSql('COMMENT ON COLUMN "contacts".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "contacts".user_id IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "contacts" DROP CONSTRAINT FK_33401573A76ED395');
        $this->addSql('DROP TABLE "contacts"');
    }
}
