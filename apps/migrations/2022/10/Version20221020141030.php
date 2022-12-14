<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221020141030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE block_breadcrumb (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', block_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_9DFE226EE9ED820C (block_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block_custom (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', block_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_BC2A4010E9ED820C (block_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block_flashbag (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', block_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_2E48474E9ED820C (block_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block_navbar (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', block_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', menu_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_A92A6D07E9ED820C (block_id), INDEX IDX_A92A6D07CCD7E912 (menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block_paragraph (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', block_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_4D3C1877E9ED820C (block_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE layout (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', custom_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(255) NOT NULL, url LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', deleted_at DATETIME DEFAULT NULL, INDEX IDX_3A3A6BE2614A603A (custom_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph_bookmark (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', paragraph_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_57C5D6DC8B50597F (paragraph_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph_bookmark_category (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', paragraph_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_956319748B50597F (paragraph_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph_bookmark_libelle (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', paragraph_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_82292FD48B50597F (paragraph_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph_bookmark_list (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', paragraph_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_344963268B50597F (paragraph_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph_edito (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', paragraph_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_B4569C888B50597F (paragraph_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph_edito_header (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', paragraph_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_635FDE498B50597F (paragraph_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph_edito_show (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', paragraph_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_F1B335EB8B50597F (paragraph_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph_history (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', paragraph_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_88F344548B50597F (paragraph_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph_history_chapter (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', paragraph_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_FE7168E18B50597F (paragraph_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph_history_list (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', paragraph_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_84D0F1A88B50597F (paragraph_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph_history_show (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', paragraph_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_F216D0B18B50597F (paragraph_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph_history_user (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', paragraph_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_4D8BDFF98B50597F (paragraph_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph_post (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', paragraph_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_89F77D228B50597F (paragraph_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph_post_archive (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', paragraph_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_233993638B50597F (paragraph_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph_post_category (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', paragraph_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_2BB833828B50597F (paragraph_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph_post_header (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', paragraph_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_129A0C148B50597F (paragraph_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph_post_libelle (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', paragraph_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_5213C9A68B50597F (paragraph_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph_post_list (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', paragraph_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_6173CA718B50597F (paragraph_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph_post_show (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', paragraph_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_17B5EB688B50597F (paragraph_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph_post_user (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', paragraph_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_A828E4208B50597F (paragraph_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph_post_year (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', paragraph_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_9E39415E8B50597F (paragraph_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE render (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE block_breadcrumb ADD CONSTRAINT FK_9DFE226EE9ED820C FOREIGN KEY (block_id) REFERENCES block (id)');
        $this->addSql('ALTER TABLE block_custom ADD CONSTRAINT FK_BC2A4010E9ED820C FOREIGN KEY (block_id) REFERENCES block (id)');
        $this->addSql('ALTER TABLE block_flashbag ADD CONSTRAINT FK_2E48474E9ED820C FOREIGN KEY (block_id) REFERENCES block (id)');
        $this->addSql('ALTER TABLE block_navbar ADD CONSTRAINT FK_A92A6D07E9ED820C FOREIGN KEY (block_id) REFERENCES block (id)');
        $this->addSql('ALTER TABLE block_navbar ADD CONSTRAINT FK_A92A6D07CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE block_paragraph ADD CONSTRAINT FK_4D3C1877E9ED820C FOREIGN KEY (block_id) REFERENCES block (id)');
        $this->addSql('ALTER TABLE layout ADD CONSTRAINT FK_3A3A6BE2614A603A FOREIGN KEY (custom_id) REFERENCES block_custom (id)');
        $this->addSql('ALTER TABLE paragraph_bookmark ADD CONSTRAINT FK_57C5D6DC8B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('ALTER TABLE paragraph_bookmark_category ADD CONSTRAINT FK_956319748B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('ALTER TABLE paragraph_bookmark_libelle ADD CONSTRAINT FK_82292FD48B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('ALTER TABLE paragraph_bookmark_list ADD CONSTRAINT FK_344963268B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('ALTER TABLE paragraph_edito ADD CONSTRAINT FK_B4569C888B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('ALTER TABLE paragraph_edito_header ADD CONSTRAINT FK_635FDE498B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('ALTER TABLE paragraph_edito_show ADD CONSTRAINT FK_F1B335EB8B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('ALTER TABLE paragraph_history ADD CONSTRAINT FK_88F344548B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('ALTER TABLE paragraph_history_chapter ADD CONSTRAINT FK_FE7168E18B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('ALTER TABLE paragraph_history_list ADD CONSTRAINT FK_84D0F1A88B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('ALTER TABLE paragraph_history_show ADD CONSTRAINT FK_F216D0B18B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('ALTER TABLE paragraph_history_user ADD CONSTRAINT FK_4D8BDFF98B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('ALTER TABLE paragraph_post ADD CONSTRAINT FK_89F77D228B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('ALTER TABLE paragraph_post_archive ADD CONSTRAINT FK_233993638B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('ALTER TABLE paragraph_post_category ADD CONSTRAINT FK_2BB833828B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('ALTER TABLE paragraph_post_header ADD CONSTRAINT FK_129A0C148B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('ALTER TABLE paragraph_post_libelle ADD CONSTRAINT FK_5213C9A68B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('ALTER TABLE paragraph_post_list ADD CONSTRAINT FK_6173CA718B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('ALTER TABLE paragraph_post_show ADD CONSTRAINT FK_17B5EB688B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('ALTER TABLE paragraph_post_user ADD CONSTRAINT FK_A828E4208B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('ALTER TABLE paragraph_post_year ADD CONSTRAINT FK_9E39415E8B50597F FOREIGN KEY (paragraph_id) REFERENCES paragraph (id)');
        $this->addSql('DROP INDEX UNIQ_831B97222B36786B ON block');
        $this->addSql('ALTER TABLE block ADD position INT NOT NULL, ADD region VARCHAR(255) NOT NULL, ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE history CHANGE summary summary LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE meta ADD render_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE meta ADD CONSTRAINT FK_D7F21435E15FA7DE FOREIGN KEY (render_id) REFERENCES render (id)');
        $this->addSql('CREATE INDEX IDX_D7F21435E15FA7DE ON meta (render_id)');
        $this->addSql('ALTER TABLE page DROP front, DROP function, DROP frontslug, CHANGE slug slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE paragraph ADD chapter_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', ADD layout_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', ADD history_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', DROP fond');
        $this->addSql('ALTER TABLE paragraph ADD CONSTRAINT FK_7DD39862579F4768 FOREIGN KEY (chapter_id) REFERENCES chapter (id)');
        $this->addSql('ALTER TABLE paragraph ADD CONSTRAINT FK_7DD398628C22AA1A FOREIGN KEY (layout_id) REFERENCES layout (id)');
        $this->addSql('ALTER TABLE paragraph ADD CONSTRAINT FK_7DD398621E058452 FOREIGN KEY (history_id) REFERENCES history (id)');
        $this->addSql('CREATE INDEX IDX_7DD39862579F4768 ON paragraph (chapter_id)');
        $this->addSql('CREATE INDEX IDX_7DD398628C22AA1A ON paragraph (layout_id)');
        $this->addSql('CREATE INDEX IDX_7DD398621E058452 ON paragraph (history_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paragraph DROP FOREIGN KEY FK_7DD398628C22AA1A');
        $this->addSql('ALTER TABLE meta DROP FOREIGN KEY FK_D7F21435E15FA7DE');
        $this->addSql('ALTER TABLE block_breadcrumb DROP FOREIGN KEY FK_9DFE226EE9ED820C');
        $this->addSql('ALTER TABLE block_custom DROP FOREIGN KEY FK_BC2A4010E9ED820C');
        $this->addSql('ALTER TABLE block_flashbag DROP FOREIGN KEY FK_2E48474E9ED820C');
        $this->addSql('ALTER TABLE block_navbar DROP FOREIGN KEY FK_A92A6D07E9ED820C');
        $this->addSql('ALTER TABLE block_navbar DROP FOREIGN KEY FK_A92A6D07CCD7E912');
        $this->addSql('ALTER TABLE block_paragraph DROP FOREIGN KEY FK_4D3C1877E9ED820C');
        $this->addSql('ALTER TABLE layout DROP FOREIGN KEY FK_3A3A6BE2614A603A');
        $this->addSql('ALTER TABLE paragraph_bookmark DROP FOREIGN KEY FK_57C5D6DC8B50597F');
        $this->addSql('ALTER TABLE paragraph_bookmark_category DROP FOREIGN KEY FK_956319748B50597F');
        $this->addSql('ALTER TABLE paragraph_bookmark_libelle DROP FOREIGN KEY FK_82292FD48B50597F');
        $this->addSql('ALTER TABLE paragraph_bookmark_list DROP FOREIGN KEY FK_344963268B50597F');
        $this->addSql('ALTER TABLE paragraph_edito DROP FOREIGN KEY FK_B4569C888B50597F');
        $this->addSql('ALTER TABLE paragraph_edito_header DROP FOREIGN KEY FK_635FDE498B50597F');
        $this->addSql('ALTER TABLE paragraph_edito_show DROP FOREIGN KEY FK_F1B335EB8B50597F');
        $this->addSql('ALTER TABLE paragraph_history DROP FOREIGN KEY FK_88F344548B50597F');
        $this->addSql('ALTER TABLE paragraph_history_chapter DROP FOREIGN KEY FK_FE7168E18B50597F');
        $this->addSql('ALTER TABLE paragraph_history_list DROP FOREIGN KEY FK_84D0F1A88B50597F');
        $this->addSql('ALTER TABLE paragraph_history_show DROP FOREIGN KEY FK_F216D0B18B50597F');
        $this->addSql('ALTER TABLE paragraph_history_user DROP FOREIGN KEY FK_4D8BDFF98B50597F');
        $this->addSql('ALTER TABLE paragraph_post DROP FOREIGN KEY FK_89F77D228B50597F');
        $this->addSql('ALTER TABLE paragraph_post_archive DROP FOREIGN KEY FK_233993638B50597F');
        $this->addSql('ALTER TABLE paragraph_post_category DROP FOREIGN KEY FK_2BB833828B50597F');
        $this->addSql('ALTER TABLE paragraph_post_header DROP FOREIGN KEY FK_129A0C148B50597F');
        $this->addSql('ALTER TABLE paragraph_post_libelle DROP FOREIGN KEY FK_5213C9A68B50597F');
        $this->addSql('ALTER TABLE paragraph_post_list DROP FOREIGN KEY FK_6173CA718B50597F');
        $this->addSql('ALTER TABLE paragraph_post_show DROP FOREIGN KEY FK_17B5EB688B50597F');
        $this->addSql('ALTER TABLE paragraph_post_user DROP FOREIGN KEY FK_A828E4208B50597F');
        $this->addSql('ALTER TABLE paragraph_post_year DROP FOREIGN KEY FK_9E39415E8B50597F');
        $this->addSql('DROP TABLE block_breadcrumb');
        $this->addSql('DROP TABLE block_custom');
        $this->addSql('DROP TABLE block_flashbag');
        $this->addSql('DROP TABLE block_navbar');
        $this->addSql('DROP TABLE block_paragraph');
        $this->addSql('DROP TABLE layout');
        $this->addSql('DROP TABLE paragraph_bookmark');
        $this->addSql('DROP TABLE paragraph_bookmark_category');
        $this->addSql('DROP TABLE paragraph_bookmark_libelle');
        $this->addSql('DROP TABLE paragraph_bookmark_list');
        $this->addSql('DROP TABLE paragraph_edito');
        $this->addSql('DROP TABLE paragraph_edito_header');
        $this->addSql('DROP TABLE paragraph_edito_show');
        $this->addSql('DROP TABLE paragraph_history');
        $this->addSql('DROP TABLE paragraph_history_chapter');
        $this->addSql('DROP TABLE paragraph_history_list');
        $this->addSql('DROP TABLE paragraph_history_show');
        $this->addSql('DROP TABLE paragraph_history_user');
        $this->addSql('DROP TABLE paragraph_post');
        $this->addSql('DROP TABLE paragraph_post_archive');
        $this->addSql('DROP TABLE paragraph_post_category');
        $this->addSql('DROP TABLE paragraph_post_header');
        $this->addSql('DROP TABLE paragraph_post_libelle');
        $this->addSql('DROP TABLE paragraph_post_list');
        $this->addSql('DROP TABLE paragraph_post_show');
        $this->addSql('DROP TABLE paragraph_post_user');
        $this->addSql('DROP TABLE paragraph_post_year');
        $this->addSql('DROP TABLE render');
        $this->addSql('DROP INDEX IDX_D7F21435E15FA7DE ON meta');
        $this->addSql('ALTER TABLE meta DROP render_id');
        $this->addSql('ALTER TABLE history CHANGE summary summary LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE page ADD front TINYINT(1) NOT NULL, ADD function VARCHAR(255) DEFAULT NULL, ADD frontslug LONGTEXT DEFAULT NULL, CHANGE slug slug VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE paragraph DROP FOREIGN KEY FK_7DD39862579F4768');
        $this->addSql('ALTER TABLE paragraph DROP FOREIGN KEY FK_7DD398621E058452');
        $this->addSql('DROP INDEX IDX_7DD39862579F4768 ON paragraph');
        $this->addSql('DROP INDEX IDX_7DD398628C22AA1A ON paragraph');
        $this->addSql('DROP INDEX IDX_7DD398621E058452 ON paragraph');
        $this->addSql('ALTER TABLE paragraph ADD fond VARCHAR(255) DEFAULT NULL, DROP chapter_id, DROP layout_id, DROP history_id');
        $this->addSql('ALTER TABLE block DROP position, DROP region, DROP deleted_at');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_831B97222B36786B ON block (title)');
    }
}
