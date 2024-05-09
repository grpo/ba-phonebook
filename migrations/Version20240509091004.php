<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240509091004 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE contact_user (
          contact_id UUID NOT NULL,
          user_id UUID NOT NULL,
          PRIMARY KEY(contact_id, user_id)
        )');
        $this->addSql('CREATE INDEX IDX_A56C54B6E7A1254A ON contact_user (contact_id)');
        $this->addSql('CREATE INDEX IDX_A56C54B6A76ED395 ON contact_user (user_id)');
        $this->addSql('COMMENT ON COLUMN contact_user.contact_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN contact_user.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE
          contact_user
        ADD
          CONSTRAINT FK_A56C54B6E7A1254A FOREIGN KEY (contact_id) REFERENCES "contacts" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          contact_user
        ADD
          CONSTRAINT FK_A56C54B6A76ED395 FOREIGN KEY (user_id) REFERENCES "users" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE contact_user DROP CONSTRAINT FK_A56C54B6E7A1254A');
        $this->addSql('ALTER TABLE contact_user DROP CONSTRAINT FK_A56C54B6A76ED395');
        $this->addSql('DROP TABLE contact_user');
    }
}
