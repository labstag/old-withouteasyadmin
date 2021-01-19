<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210118143339 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adresse ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE configuration ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE edito ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE email ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE geo_code ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE groupe ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE lien ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE menu ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE note_interne ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE phone ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE template ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD deleted_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adresse DROP deleted_at');
        $this->addSql('ALTER TABLE configuration DROP deleted_at');
        $this->addSql('ALTER TABLE edito DROP deleted_at');
        $this->addSql('ALTER TABLE email DROP deleted_at');
        $this->addSql('ALTER TABLE geo_code DROP deleted_at');
        $this->addSql('ALTER TABLE groupe DROP deleted_at');
        $this->addSql('ALTER TABLE lien DROP deleted_at');
        $this->addSql('ALTER TABLE menu DROP deleted_at');
        $this->addSql('ALTER TABLE note_interne DROP deleted_at');
        $this->addSql('ALTER TABLE phone DROP deleted_at');
        $this->addSql('ALTER TABLE template DROP deleted_at');
        $this->addSql('ALTER TABLE user DROP deleted_at');
    }
}
