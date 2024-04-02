<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240402140330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE http_error_logs ADD refuser_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE http_error_logs ADD CONSTRAINT FK_4B946BCF2B445CEF FOREIGN KEY (refuser_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_4B946BCF2B445CEF ON http_error_logs (refuser_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE http_error_logs DROP FOREIGN KEY FK_4B946BCF2B445CEF');
        $this->addSql('DROP INDEX IDX_4B946BCF2B445CEF ON http_error_logs');
        $this->addSql('ALTER TABLE http_error_logs DROP refuser_id');
    }
}
