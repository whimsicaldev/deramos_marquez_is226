<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220518162114 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE settle_history (id INT AUTO_INCREMENT NOT NULL, connection_id INT NOT NULL, amount NUMERIC(10, 2) NOT NULL, INDEX IDX_D62173A4DD03F01 (connection_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE settle_history ADD CONSTRAINT FK_D62173A4DD03F01 FOREIGN KEY (connection_id) REFERENCES connection (id)');
        $this->addSql('ALTER TABLE connection ADD user_amount_owed NUMERIC(10, 2) NOT NULL, ADD peer_amount_owed NUMERIC(10, 2) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE settle_history');
        $this->addSql('ALTER TABLE connection DROP user_amount_owed, DROP peer_amount_owed');
    }
}
