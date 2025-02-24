<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241021073428 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE element DROP FOREIGN KEY FK_41405E39DF1B2C7C');
        $this->addSql('DROP INDEX IDX_41405E39DF1B2C7C ON element');
        $this->addSql('ALTER TABLE element DROP squad_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE element ADD squad_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE element ADD CONSTRAINT FK_41405E39DF1B2C7C FOREIGN KEY (squad_id) REFERENCES squad (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_41405E39DF1B2C7C ON element (squad_id)');
    }
}
