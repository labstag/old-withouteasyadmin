<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211110133228 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chapter (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', refhistory_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', content LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, meta_description VARCHAR(255) DEFAULT NULL, meta_keywords VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, position INT NOT NULL, published DATETIME NOT NULL, slug VARCHAR(255) NOT NULL, updated DATETIME NOT NULL, pages INT NOT NULL, deleted_at DATETIME DEFAULT NULL, state LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', state_changed DATETIME DEFAULT NULL, INDEX IDX_F981B52E20C3240A (refhistory_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE history (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', refuser_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', created DATETIME NOT NULL, meta_description VARCHAR(255) DEFAULT NULL, meta_keywords VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, published DATETIME NOT NULL, slug VARCHAR(255) NOT NULL, summary LONGTEXT NOT NULL, updated DATETIME NOT NULL, pages INT NOT NULL, deleted_at DATETIME DEFAULT NULL, state LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', state_changed DATETIME DEFAULT NULL, INDEX IDX_27BA704B2B445CEF (refuser_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chapter ADD CONSTRAINT FK_F981B52E20C3240A FOREIGN KEY (refhistory_id) REFERENCES history (id)');
        $this->addSql('ALTER TABLE history ADD CONSTRAINT FK_27BA704B2B445CEF FOREIGN KEY (refuser_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chapter DROP FOREIGN KEY FK_F981B52E20C3240A');
        $this->addSql('DROP TABLE chapter');
        $this->addSql('DROP TABLE history');
    }
}
