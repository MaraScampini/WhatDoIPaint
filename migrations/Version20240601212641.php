<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240601212641 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE brand (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, is_generic TINYINT(1) NOT NULL, image VARCHAR(255) DEFAULT NULL, INDEX IDX_1C52F958A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, reference_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_D8698A761645DEA9 (reference_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE element (id INT AUTO_INCREMENT NOT NULL, status_id INT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, last_update DATETIME NOT NULL, INDEX IDX_41405E396BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE element_update (id INT AUTO_INCREMENT NOT NULL, element_id INT NOT NULL, new_update_id INT NOT NULL, INDEX IDX_11323CEC1F1F2A24 (element_id), INDEX IDX_11323CECE709EFDE (new_update_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE level (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, status_id INT NOT NULL, level_id INT NOT NULL, brand_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, creation_date DATETIME NOT NULL, last_update DATETIME NOT NULL, is_priority TINYINT(1) NOT NULL, image VARCHAR(255) DEFAULT NULL, INDEX IDX_2FB3D0EE6BF700BD (status_id), INDEX IDX_2FB3D0EE5FB14BA7 (level_id), INDEX IDX_2FB3D0EE44F5D008 (brand_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_technique (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, technique_id INT NOT NULL, INDEX IDX_C607DD96166D1F9C (project_id), INDEX IDX_C607DD961F8ACB26 (technique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reference (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, url VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, INDEX IDX_AEA34913166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE technique (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `update` (id INT AUTO_INCREMENT NOT NULL, description LONGTEXT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, date DATETIME NOT NULL, last_update DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE brand ADD CONSTRAINT FK_1C52F958A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A761645DEA9 FOREIGN KEY (reference_id) REFERENCES reference (id)');
        $this->addSql('ALTER TABLE element ADD CONSTRAINT FK_41405E396BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE element_update ADD CONSTRAINT FK_11323CEC1F1F2A24 FOREIGN KEY (element_id) REFERENCES element (id)');
        $this->addSql('ALTER TABLE element_update ADD CONSTRAINT FK_11323CECE709EFDE FOREIGN KEY (new_update_id) REFERENCES `update` (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE6BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE5FB14BA7 FOREIGN KEY (level_id) REFERENCES level (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE44F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)');
        $this->addSql('ALTER TABLE project_technique ADD CONSTRAINT FK_C607DD96166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE project_technique ADD CONSTRAINT FK_C607DD961F8ACB26 FOREIGN KEY (technique_id) REFERENCES technique (id)');
        $this->addSql('ALTER TABLE reference ADD CONSTRAINT FK_AEA34913166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE brand DROP FOREIGN KEY FK_1C52F958A76ED395');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A761645DEA9');
        $this->addSql('ALTER TABLE element DROP FOREIGN KEY FK_41405E396BF700BD');
        $this->addSql('ALTER TABLE element_update DROP FOREIGN KEY FK_11323CEC1F1F2A24');
        $this->addSql('ALTER TABLE element_update DROP FOREIGN KEY FK_11323CECE709EFDE');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE6BF700BD');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE5FB14BA7');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE44F5D008');
        $this->addSql('ALTER TABLE project_technique DROP FOREIGN KEY FK_C607DD96166D1F9C');
        $this->addSql('ALTER TABLE project_technique DROP FOREIGN KEY FK_C607DD961F8ACB26');
        $this->addSql('ALTER TABLE reference DROP FOREIGN KEY FK_AEA34913166D1F9C');
        $this->addSql('DROP TABLE brand');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE element');
        $this->addSql('DROP TABLE element_update');
        $this->addSql('DROP TABLE level');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_technique');
        $this->addSql('DROP TABLE reference');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE technique');
        $this->addSql('DROP TABLE `update`');
    }
}
