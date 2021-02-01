<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210201082850 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE file (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(255) NOT NULL, file VARCHAR(255) NOT NULL, extension VARCHAR(255) NOT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE edito ADD fond_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE edito ADD CONSTRAINT FK_F2EC5FE087EEAE74 FOREIGN KEY (fond_id) REFERENCES file (id)');
        $this->addSql('CREATE INDEX IDX_F2EC5FE087EEAE74 ON edito (fond_id)');
        $this->addSql('ALTER TABLE note_interne ADD fond_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE note_interne ADD CONSTRAINT FK_A163520687EEAE74 FOREIGN KEY (fond_id) REFERENCES file (id)');
        $this->addSql('CREATE INDEX IDX_A163520687EEAE74 ON note_interne (fond_id)');
        $this->addSql('ALTER TABLE user ADD avatar_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64986383B10 FOREIGN KEY (avatar_id) REFERENCES file (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64986383B10 ON user (avatar_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE edito DROP FOREIGN KEY FK_F2EC5FE087EEAE74');
        $this->addSql('ALTER TABLE note_interne DROP FOREIGN KEY FK_A163520687EEAE74');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64986383B10');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP INDEX IDX_F2EC5FE087EEAE74 ON edito');
        $this->addSql('ALTER TABLE edito DROP fond_id');
        $this->addSql('DROP INDEX IDX_A163520687EEAE74 ON note_interne');
        $this->addSql('ALTER TABLE note_interne DROP fond_id');
        $this->addSql('DROP INDEX IDX_8D93D64986383B10 ON user');
        $this->addSql('ALTER TABLE user DROP avatar_id');
    }
}
