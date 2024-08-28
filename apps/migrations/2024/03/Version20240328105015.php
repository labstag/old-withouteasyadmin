<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240328105015 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE http_error_logs (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', agent VARCHAR(255) NOT NULL, date_time DATETIME NOT NULL, domain LONGTEXT NOT NULL, http_code INT NOT NULL, internet_protocol VARCHAR(255) NOT NULL, referer LONGTEXT DEFAULT NULL, request_data JSON NOT NULL COMMENT \'(DC2Type:json)\', request_method VARCHAR(255) NOT NULL, url LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE redirection (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', action_code INT NOT NULL, action_type INT NOT NULL, date_time DATETIME NOT NULL, data JSON NOT NULL COMMENT \'(DC2Type:json)\', destination LONGTEXT NOT NULL, enable TINYINT(1) NOT NULL, last_count INT DEFAULT 0 NOT NULL, position INT NOT NULL, regex TINYINT(1) NOT NULL, source LONGTEXT NOT NULL, title VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE http_error_logs');
        $this->addSql('DROP TABLE redirection');
    }
}
