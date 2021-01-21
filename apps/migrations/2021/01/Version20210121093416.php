<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210121093416 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE edito DROP enable');
        $this->addSql('ALTER TABLE email DROP verif');
        $this->addSql('ALTER TABLE note_interne DROP enable');
        $this->addSql('ALTER TABLE user DROP enable, DROP lost, DROP verif');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE edito ADD enable TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE email ADD verif TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE note_interne ADD enable TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE user ADD enable TINYINT(1) NOT NULL, ADD lost TINYINT(1) NOT NULL, ADD verif TINYINT(1) NOT NULL');
    }
}
