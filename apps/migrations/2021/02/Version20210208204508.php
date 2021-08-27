<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210208204508 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE workflow (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', entity VARCHAR(255) NOT NULL, transition VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE workflow_groupe (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', refgroupe_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', refworkflow_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', state TINYINT(1) NOT NULL, INDEX IDX_25BC36E2D292B66B (refgroupe_id), INDEX IDX_25BC36E249F233DC (refworkflow_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE workflow_user (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', refuser_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', refworkflow_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', state TINYINT(1) NOT NULL, INDEX IDX_C80CC6722B445CEF (refuser_id), INDEX IDX_C80CC67249F233DC (refworkflow_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE workflow_groupe ADD CONSTRAINT FK_25BC36E2D292B66B FOREIGN KEY (refgroupe_id) REFERENCES groupe (id)');
        $this->addSql('ALTER TABLE workflow_groupe ADD CONSTRAINT FK_25BC36E249F233DC FOREIGN KEY (refworkflow_id) REFERENCES workflow (id)');
        $this->addSql('ALTER TABLE workflow_user ADD CONSTRAINT FK_C80CC6722B445CEF FOREIGN KEY (refuser_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE workflow_user ADD CONSTRAINT FK_C80CC67249F233DC FOREIGN KEY (refworkflow_id) REFERENCES workflow (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE workflow_groupe DROP FOREIGN KEY FK_25BC36E249F233DC');
        $this->addSql('ALTER TABLE workflow_user DROP FOREIGN KEY FK_C80CC67249F233DC');
        $this->addSql('DROP TABLE workflow');
        $this->addSql('DROP TABLE workflow_groupe');
        $this->addSql('DROP TABLE workflow_user');
    }
}
