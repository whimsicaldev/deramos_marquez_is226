<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220518134332 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE expense DROP FOREIGN KEY FK_2D3A8DA620D91DB4');
        $this->addSql('ALTER TABLE expense DROP FOREIGN KEY FK_2D3A8DA6A76ED395');
        $this->addSql('ALTER TABLE expense DROP FOREIGN KEY FK_2D3A8DA6B9ADA51B');
        $this->addSql('DROP INDEX IDX_2D3A8DA620D91DB4 ON expense');
        $this->addSql('DROP INDEX IDX_2D3A8DA6A76ED395 ON expense');
        $this->addSql('DROP INDEX IDX_2D3A8DA6B9ADA51B ON expense');
        $this->addSql('ALTER TABLE expense DROP in_group_id, DROP peer_id, DROP is_personal, CHANGE user_id created_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE expense ADD CONSTRAINT FK_2D3A8DA6B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2D3A8DA6B03A8386 ON expense (created_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE expense DROP FOREIGN KEY FK_2D3A8DA6B03A8386');
        $this->addSql('DROP INDEX IDX_2D3A8DA6B03A8386 ON expense');
        $this->addSql('ALTER TABLE expense ADD in_group_id INT DEFAULT NULL, ADD peer_id INT DEFAULT NULL, ADD is_personal TINYINT(1) NOT NULL, CHANGE created_by_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE expense ADD CONSTRAINT FK_2D3A8DA620D91DB4 FOREIGN KEY (peer_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE expense ADD CONSTRAINT FK_2D3A8DA6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE expense ADD CONSTRAINT FK_2D3A8DA6B9ADA51B FOREIGN KEY (in_group_id) REFERENCES `group` (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_2D3A8DA620D91DB4 ON expense (peer_id)');
        $this->addSql('CREATE INDEX IDX_2D3A8DA6A76ED395 ON expense (user_id)');
        $this->addSql('CREATE INDEX IDX_2D3A8DA6B9ADA51B ON expense (in_group_id)');
    }
}
