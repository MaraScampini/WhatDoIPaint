<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240930100931 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE squad (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_CFD0FFE7166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE squad ADD CONSTRAINT FK_CFD0FFE7166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE element ADD squad_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE element ADD CONSTRAINT FK_41405E39DF1B2C7C FOREIGN KEY (squad_id) REFERENCES squad (id)');
        $this->addSql('CREATE INDEX IDX_41405E39DF1B2C7C ON element (squad_id)');
        $this->addSql('ALTER TABLE element_update ADD squad_id INT DEFAULT NULL, CHANGE element_id element_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE element_update ADD CONSTRAINT FK_11323CECDF1B2C7C FOREIGN KEY (squad_id) REFERENCES squad (id)');
        $this->addSql('CREATE INDEX IDX_11323CECDF1B2C7C ON element_update (squad_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE element DROP FOREIGN KEY FK_41405E39DF1B2C7C');
        $this->addSql('ALTER TABLE element_update DROP FOREIGN KEY FK_11323CECDF1B2C7C');
        $this->addSql('ALTER TABLE squad DROP FOREIGN KEY FK_CFD0FFE7166D1F9C');
        $this->addSql('DROP TABLE squad');
        $this->addSql('DROP INDEX IDX_11323CECDF1B2C7C ON element_update');
        $this->addSql('ALTER TABLE element_update DROP squad_id, CHANGE element_id element_id INT NOT NULL');
        $this->addSql('DROP INDEX IDX_41405E39DF1B2C7C ON element');
        $this->addSql('ALTER TABLE element DROP squad_id');
    }
}
