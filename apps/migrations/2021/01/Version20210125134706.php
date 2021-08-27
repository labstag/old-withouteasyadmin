<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210125134706 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE route (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE route_groupe (id INT AUTO_INCREMENT NOT NULL, refgroupe_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', refroute_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', state TINYINT(1) NOT NULL, INDEX IDX_354A7DFCD292B66B (refgroupe_id), INDEX IDX_354A7DFC84B0064B (refroute_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE route_user (id INT AUTO_INCREMENT NOT NULL, refuser_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', refroute_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', state TINYINT(1) NOT NULL, INDEX IDX_20D6D7CC2B445CEF (refuser_id), INDEX IDX_20D6D7CC84B0064B (refroute_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE route_groupe ADD CONSTRAINT FK_354A7DFCD292B66B FOREIGN KEY (refgroupe_id) REFERENCES groupe (id)');
        $this->addSql('ALTER TABLE route_groupe ADD CONSTRAINT FK_354A7DFC84B0064B FOREIGN KEY (refroute_id) REFERENCES route (id)');
        $this->addSql('ALTER TABLE route_user ADD CONSTRAINT FK_20D6D7CC2B445CEF FOREIGN KEY (refuser_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE route_user ADD CONSTRAINT FK_20D6D7CC84B0064B FOREIGN KEY (refroute_id) REFERENCES route (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE route_groupe DROP FOREIGN KEY FK_354A7DFC84B0064B');
        $this->addSql('ALTER TABLE route_user DROP FOREIGN KEY FK_20D6D7CC84B0064B');
        $this->addSql('DROP TABLE route');
        $this->addSql('DROP TABLE route_groupe');
        $this->addSql('DROP TABLE route_user');
    }
}
