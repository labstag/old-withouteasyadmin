<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201221215206 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE geo_code (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', country_code VARCHAR(2) NOT NULL, postal_code VARCHAR(20) NOT NULL, place_name VARCHAR(180) NOT NULL, state_name VARCHAR(100) NOT NULL, state_code VARCHAR(20) NOT NULL, province_name VARCHAR(100) NOT NULL, province_code VARCHAR(20) NOT NULL, community_name VARCHAR(100) NOT NULL, community_code VARCHAR(20) NOT NULL, latitude VARCHAR(255) NOT NULL, longitude VARCHAR(255) NOT NULL, accuracy INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE geo_code');
    }
}
