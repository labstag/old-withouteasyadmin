<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230310134611 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE paragraph_image (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', image_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', paragraph_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', slug VARCHAR(255) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, INDEX IDX_8387C7373DA5256D (image_id), INDEX IDX_8387C7378B50597F (paragraph_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph_textimage (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', image_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', paragraph_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', content LONGTEXT DEFAULT NULL, leftimage TINYINT(1) DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, INDEX IDX_73885F133DA5256D (image_id), INDEX IDX_73885F138B50597F (paragraph_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph_video (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', image_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', paragraph_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', slug VARCHAR(255) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, INDEX IDX_3A7D19443DA5256D (image_id), INDEX IDX_3A7D19448B50597F (paragraph_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE paragraph_image ADD CONSTRAINT FK_8387C7373DA5256D FOREIGN KEY (image_id) REFERENCES attachment (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE paragraph_image ADD CONSTRAINT FK_8387C7378B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('ALTER TABLE paragraph_textimage ADD CONSTRAINT FK_73885F133DA5256D FOREIGN KEY (image_id) REFERENCES attachment (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE paragraph_textimage ADD CONSTRAINT FK_73885F138B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('ALTER TABLE paragraph_video ADD CONSTRAINT FK_3A7D19443DA5256D FOREIGN KEY (image_id) REFERENCES attachment (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE paragraph_video ADD CONSTRAINT FK_3A7D19448B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('ALTER TABLE oauth_connect_user CHANGE refuser_id refuser_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paragraph_image DROP FOREIGN KEY FK_8387C7373DA5256D');
        $this->addSql('ALTER TABLE paragraph_image DROP FOREIGN KEY FK_8387C7378B50597F');
        $this->addSql('ALTER TABLE paragraph_textimage DROP FOREIGN KEY FK_73885F133DA5256D');
        $this->addSql('ALTER TABLE paragraph_textimage DROP FOREIGN KEY FK_73885F138B50597F');
        $this->addSql('ALTER TABLE paragraph_video DROP FOREIGN KEY FK_3A7D19443DA5256D');
        $this->addSql('ALTER TABLE paragraph_video DROP FOREIGN KEY FK_3A7D19448B50597F');
        $this->addSql('DROP TABLE paragraph_image');
        $this->addSql('DROP TABLE paragraph_textimage');
        $this->addSql('DROP TABLE paragraph_video');
        $this->addSql('ALTER TABLE oauth_connect_user CHANGE refuser_id refuser_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
    }
}
