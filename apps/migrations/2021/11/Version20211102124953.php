<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211102124953 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lien RENAME to link');
        $this->addSql('ALTER TABLE link DROP FOREIGN KEY FK_A532B4B52B445CEF');
        $this->addSql('DROP INDEX idx_a532b4b52b445cef ON link');
        $this->addSql('CREATE INDEX IDX_36AC99F12B445CEF ON link (refuser_id)');
        $this->addSql('ALTER TABLE link ADD CONSTRAINT FK_A532B4B52B445CEF FOREIGN KEY (refuser_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE link RENAME to lien');
        $this->addSql('ALTER TABLE link DROP FOREIGN KEY FK_36AC99F12B445CEF');
        $this->addSql('DROP INDEX idx_36ac99f12b445cef ON link');
        $this->addSql('CREATE INDEX IDX_A532B4B52B445CEF ON link (refuser_id)');
        $this->addSql('ALTER TABLE link ADD CONSTRAINT FK_36AC99F12B445CEF FOREIGN KEY (refuser_id) REFERENCES user (id)');
    }
}
