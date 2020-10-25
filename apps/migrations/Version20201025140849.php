<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201025140849 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE edito CHANGE refuser_id refuser_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE note_interne ADD refuser_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE note_interne ADD CONSTRAINT FK_A16352062B445CEF FOREIGN KEY (refuser_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_A16352062B445CEF ON note_interne (refuser_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE edito CHANGE refuser_id refuser_id CHAR(36) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE note_interne DROP FOREIGN KEY FK_A16352062B445CEF');
        $this->addSql('DROP INDEX IDX_A16352062B445CEF ON note_interne');
        $this->addSql('ALTER TABLE note_interne DROP refuser_id');
    }
}
