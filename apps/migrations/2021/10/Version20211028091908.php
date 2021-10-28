<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211028091908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adresse RENAME to address');
        $this->addSql('ALTER TABLE address CHANGE ville city VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE address CHANGE rue street VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE email CHANGE adresse address VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE lien CHANGE adresse address VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_C35F08162B445CEF');
        $this->addSql('DROP INDEX idx_c35f08162b445cef ON address');
        $this->addSql('CREATE INDEX IDX_D4E6F812B445CEF ON address (refuser_id)');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_C35F08162B445CEF FOREIGN KEY (refuser_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE libelle CHANGE nom name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE post CHANGE commentaire remark TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE note_interne RENAME to memo');
        $this->addSql('ALTER TABLE memo DROP FOREIGN KEY FK_A16352062B445CEF');
        $this->addSql('ALTER TABLE memo DROP FOREIGN KEY FK_A163520687EEAE74');
        $this->addSql('DROP INDEX uniq_a16352062b36786b ON memo');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AB4A902A2B36786B ON memo (title)');
        $this->addSql('DROP INDEX idx_a163520687eeae74 ON memo');
        $this->addSql('CREATE INDEX IDX_AB4A902A87EEAE74 ON memo (fond_id)');
        $this->addSql('DROP INDEX idx_a16352062b445cef ON memo');
        $this->addSql('CREATE INDEX IDX_AB4A902A2B445CEF ON memo (refuser_id)');
        $this->addSql('ALTER TABLE memo ADD CONSTRAINT FK_A16352062B445CEF FOREIGN KEY (refuser_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE memo ADD CONSTRAINT FK_A163520687EEAE74 FOREIGN KEY (fond_id) REFERENCES attachment (id)');
        $this->addSql('ALTER TABLE menu CHANGE libelle name VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address RENAME to adresse');
        $this->addSql('ALTER TABLE address CHANGE city ville VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE address CHANGE street rue VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE email CHANGE address adresse VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE lien CHANGE address adresse VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F812B445CEF');
        $this->addSql('DROP INDEX idx_d4e6f812b445cef ON address');
        $this->addSql('CREATE INDEX IDX_C35F08162B445CEF ON address (refuser_id)');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F812B445CEF FOREIGN KEY (refuser_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE libelle CHANGE name nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE post CHANGE remark commentaire TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE memo RENAME to note_interne');
        $this->addSql('ALTER TABLE memo DROP FOREIGN KEY FK_AB4A902A87EEAE74');
        $this->addSql('ALTER TABLE memo DROP FOREIGN KEY FK_AB4A902A2B445CEF');
        $this->addSql('DROP INDEX uniq_ab4a902a2b36786b ON memo');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A16352062B36786B ON memo (title)');
        $this->addSql('DROP INDEX idx_ab4a902a2b445cef ON memo');
        $this->addSql('CREATE INDEX IDX_A16352062B445CEF ON memo (refuser_id)');
        $this->addSql('DROP INDEX idx_ab4a902a87eeae74 ON memo');
        $this->addSql('CREATE INDEX IDX_A163520687EEAE74 ON memo (fond_id)');
        $this->addSql('ALTER TABLE memo ADD CONSTRAINT FK_AB4A902A87EEAE74 FOREIGN KEY (fond_id) REFERENCES attachment (id)');
        $this->addSql('ALTER TABLE memo ADD CONSTRAINT FK_AB4A902A2B445CEF FOREIGN KEY (refuser_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE menu CHANGE name libelle VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
