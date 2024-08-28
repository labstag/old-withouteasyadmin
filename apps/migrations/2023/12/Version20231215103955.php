<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231215103955 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE block_groupe (block_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', groupe_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_4D17DAAAE9ED820C (block_id), INDEX IDX_4D17DAAA7A45358C (groupe_id), PRIMARY KEY(block_id, groupe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block_page (block_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', page_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_D264E45BE9ED820C (block_id), INDEX IDX_D264E45BC4663E4 (page_id), PRIMARY KEY(block_id, page_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE layout_groupe (layout_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', groupe_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_7F819BE78C22AA1A (layout_id), INDEX IDX_7F819BE77A45358C (groupe_id), PRIMARY KEY(layout_id, groupe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph_form (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', paragraph_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', form VARCHAR(255) DEFAULT NULL, INDEX IDX_81F5ECE08B50597F (paragraph_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE block_groupe ADD CONSTRAINT FK_4D17DAAAE9ED820C FOREIGN KEY (block_id) REFERENCES block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_groupe ADD CONSTRAINT FK_4D17DAAA7A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_page ADD CONSTRAINT FK_D264E45BE9ED820C FOREIGN KEY (block_id) REFERENCES block (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE block_page ADD CONSTRAINT FK_D264E45BC4663E4 FOREIGN KEY (page_id) REFERENCES page (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE layout_groupe ADD CONSTRAINT FK_7F819BE78C22AA1A FOREIGN KEY (layout_id) REFERENCES layout (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE layout_groupe ADD CONSTRAINT FK_7F819BE77A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE paragraph_form ADD CONSTRAINT FK_81F5ECE08B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('ALTER TABLE menu CHANGE data data JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE messenger_messages CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE available_at available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE delivered_at delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE rememberme_token CHANGE lastUsed lastUsed DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE block_groupe DROP FOREIGN KEY FK_4D17DAAAE9ED820C');
        $this->addSql('ALTER TABLE block_groupe DROP FOREIGN KEY FK_4D17DAAA7A45358C');
        $this->addSql('ALTER TABLE block_page DROP FOREIGN KEY FK_D264E45BE9ED820C');
        $this->addSql('ALTER TABLE block_page DROP FOREIGN KEY FK_D264E45BC4663E4');
        $this->addSql('ALTER TABLE layout_groupe DROP FOREIGN KEY FK_7F819BE78C22AA1A');
        $this->addSql('ALTER TABLE layout_groupe DROP FOREIGN KEY FK_7F819BE77A45358C');
        $this->addSql('ALTER TABLE paragraph_form DROP FOREIGN KEY FK_81F5ECE08B50597F');
        $this->addSql('DROP TABLE block_groupe');
        $this->addSql('DROP TABLE block_page');
        $this->addSql('DROP TABLE layout_groupe');
        $this->addSql('DROP TABLE paragraph_form');
        $this->addSql('ALTER TABLE messenger_messages CHANGE created_at created_at DATETIME NOT NULL, CHANGE available_at available_at DATETIME NOT NULL, CHANGE delivered_at delivered_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE menu CHANGE data data JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE rememberme_token CHANGE lastUsed lastUsed DATETIME NOT NULL');
    }
}
