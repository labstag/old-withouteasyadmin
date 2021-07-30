<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210727131927 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE edito ADD meta_description VARCHAR(255) NOT NULL, ADD meta_keywords VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE post ADD meta_description VARCHAR(255) NOT NULL, ADD meta_keywords VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE edito DROP meta_description, DROP meta_keywords');
        $this->addSql('ALTER TABLE post DROP meta_description, DROP meta_keywords');
    }
}
