<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210926210722 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bookmark (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', img_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', refcategory_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', refuser_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', content LONGTEXT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, state LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', url VARCHAR(255) NOT NULL, meta_description VARCHAR(255) NOT NULL, meta_keywords VARCHAR(255) NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_DA62921DC06A9F55 (img_id), INDEX IDX_DA62921D77C88284 (refcategory_id), INDEX IDX_DA62921D2B445CEF (refuser_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bookmark_libelle (bookmark_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', libelle_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_C90E6C6292741D25 (bookmark_id), INDEX IDX_C90E6C6225DD318D (libelle_id), PRIMARY KEY(bookmark_id, libelle_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bookmark ADD CONSTRAINT FK_DA62921DC06A9F55 FOREIGN KEY (img_id) REFERENCES attachment (id)');
        $this->addSql('ALTER TABLE bookmark ADD CONSTRAINT FK_DA62921D77C88284 FOREIGN KEY (refcategory_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE bookmark ADD CONSTRAINT FK_DA62921D2B445CEF FOREIGN KEY (refuser_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE bookmark_libelle ADD CONSTRAINT FK_C90E6C6292741D25 FOREIGN KEY (bookmark_id) REFERENCES bookmark (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bookmark_libelle ADD CONSTRAINT FK_C90E6C6225DD318D FOREIGN KEY (libelle_id) REFERENCES libelle (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE email CHANGE refuser_id refuser_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE lien CHANGE refuser_id refuser_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE post CHANGE refuser_id refuser_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bookmark_libelle DROP FOREIGN KEY FK_C90E6C6292741D25');
        $this->addSql('DROP TABLE bookmark');
        $this->addSql('DROP TABLE bookmark_libelle');
        $this->addSql('ALTER TABLE email CHANGE refuser_id refuser_id CHAR(36) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE lien CHANGE refuser_id refuser_id CHAR(36) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE post CHANGE refuser_id refuser_id CHAR(36) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\'');
    }
}
