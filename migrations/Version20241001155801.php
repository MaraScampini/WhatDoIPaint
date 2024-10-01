<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241001155801 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `update` ADD project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `update` ADD CONSTRAINT FK_98253578166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('CREATE INDEX IDX_98253578166D1F9C ON `update` (project_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `update` DROP FOREIGN KEY FK_98253578166D1F9C');
        $this->addSql('DROP INDEX IDX_98253578166D1F9C ON `update`');
        $this->addSql('ALTER TABLE `update` DROP project_id');
    }
}
