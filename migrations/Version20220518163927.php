<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220518163927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE connection ADD user_debt NUMERIC(10, 2) NOT NULL, ADD peer_debt NUMERIC(10, 2) NOT NULL, DROP user_amount_owed, DROP peer_amount_owed');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE connection ADD user_amount_owed NUMERIC(10, 2) NOT NULL, ADD peer_amount_owed NUMERIC(10, 2) NOT NULL, DROP user_debt, DROP peer_debt');
    }
}
