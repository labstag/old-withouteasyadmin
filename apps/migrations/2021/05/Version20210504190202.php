<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210504190202 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE libelle (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', nom VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE libelle_post (libelle_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', post_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_EC14B9FA25DD318D (libelle_id), INDEX IDX_EC14B9FA4B89032C (post_id), PRIMARY KEY(libelle_id, post_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', refuser_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, img VARCHAR(255) DEFAULT NULL, content LONGTEXT NOT NULL, commentaire TINYINT(1) NOT NULL, state LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', deleted_at DATETIME DEFAULT NULL, INDEX IDX_5A8A6C8D2B445CEF (refuser_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE libelle_post ADD CONSTRAINT FK_EC14B9FA25DD318D FOREIGN KEY (libelle_id) REFERENCES libelle (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE libelle_post ADD CONSTRAINT FK_EC14B9FA4B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D2B445CEF FOREIGN KEY (refuser_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE libelle_post DROP FOREIGN KEY FK_EC14B9FA25DD318D');
        $this->addSql('ALTER TABLE libelle_post DROP FOREIGN KEY FK_EC14B9FA4B89032C');
        $this->addSql('DROP TABLE libelle');
        $this->addSql('DROP TABLE libelle_post');
        $this->addSql('DROP TABLE post');
    }
}
