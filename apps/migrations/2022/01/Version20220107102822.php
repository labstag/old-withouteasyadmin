<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220107102822 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE layout (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', content LONGTEXT DEFAULT NULL, name VARCHAR(255) NOT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE page ADD reflayout_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB62024F529FD FOREIGN KEY (reflayout_id) REFERENCES layout (id)');
        $this->addSql('CREATE INDEX IDX_140AB62024F529FD ON page (reflayout_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB62024F529FD');
        $this->addSql('DROP TABLE layout');
        $this->addSql('DROP INDEX IDX_140AB62024F529FD ON page');
        $this->addSql('ALTER TABLE page DROP reflayout_id');
    }
}
