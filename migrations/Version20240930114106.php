<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240930114106 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, new_update_id INT DEFAULT NULL, project_id INT DEFAULT NULL, user_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_C53D045FE709EFDE (new_update_id), INDEX IDX_C53D045F166D1F9C (project_id), INDEX IDX_C53D045FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FE709EFDE FOREIGN KEY (new_update_id) REFERENCES `update` (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE element DROP image');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FE709EFDE');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F166D1F9C');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FA76ED395');
        $this->addSql('DROP TABLE image');
        $this->addSql('ALTER TABLE element ADD image VARCHAR(255) DEFAULT NULL');
    }
}
