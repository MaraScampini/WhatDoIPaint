<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241021065946 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE squad_status (id INT AUTO_INCREMENT NOT NULL, squad_id INT NOT NULL, status_id INT DEFAULT NULL, amount INT NOT NULL, INDEX IDX_72B30C3DF1B2C7C (squad_id), INDEX IDX_72B30C36BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE squad_status ADD CONSTRAINT FK_72B30C3DF1B2C7C FOREIGN KEY (squad_id) REFERENCES squad (id)');
        $this->addSql('ALTER TABLE squad_status ADD CONSTRAINT FK_72B30C36BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE squad_status DROP FOREIGN KEY FK_72B30C3DF1B2C7C');
        $this->addSql('ALTER TABLE squad_status DROP FOREIGN KEY FK_72B30C36BF700BD');
        $this->addSql('DROP TABLE squad_status');
    }
}
