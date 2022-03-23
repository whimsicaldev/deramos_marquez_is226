<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220323082530 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE connection (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, peer_id INT NOT NULL, amount NUMERIC(10, 2) NOT NULL, status ENUM(\'requested\', \'approved\', \'denied\', \'blocked\') NOT NULL COMMENT \'(DC2Type:enumconnection)\', INDEX IDX_29F77366A76ED395 (user_id), INDEX IDX_29F7736620D91DB4 (peer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE expense (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, user_id INT NOT NULL, in_group_id INT DEFAULT NULL, peer_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, total_amount NUMERIC(10, 0) NOT NULL, date DATETIME NOT NULL, is_personal TINYINT(1) NOT NULL, INDEX IDX_2D3A8DA612469DE2 (category_id), INDEX IDX_2D3A8DA6A76ED395 (user_id), INDEX IDX_2D3A8DA6B9ADA51B (in_group_id), INDEX IDX_2D3A8DA620D91DB4 (peer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `group` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, is_active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE loan (id INT AUTO_INCREMENT NOT NULL, lender_id INT NOT NULL, borrower_id INT NOT NULL, payment_id INT NOT NULL, expense_id INT NOT NULL, category_id INT NOT NULL, amount NUMERIC(10, 0) NOT NULL, date DATETIME NOT NULL, INDEX IDX_C5D30D03855D3E3D (lender_id), INDEX IDX_C5D30D0311CE312B (borrower_id), INDEX IDX_C5D30D034C3A3BB (payment_id), INDEX IDX_C5D30D03F395DB7B (expense_id), INDEX IDX_C5D30D0312469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, expense_id INT NOT NULL, user_id INT NOT NULL, category_id INT NOT NULL, paid_amount NUMERIC(10, 0) NOT NULL, shared_amount NUMERIC(10, 0) NOT NULL, date DATETIME NOT NULL, INDEX IDX_6D28840DF395DB7B (expense_id), INDEX IDX_6D28840DA76ED395 (user_id), INDEX IDX_6D28840D12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE settlement (id INT AUTO_INCREMENT NOT NULL, lender_id INT NOT NULL, payer_id INT NOT NULL, amount NUMERIC(10, 0) NOT NULL, date DATETIME NOT NULL, INDEX IDX_DD9F1B51855D3E3D (lender_id), INDEX IDX_DD9F1B51C17AD9A9 (payer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, peer_id INT NOT NULL, from_group_id INT DEFAULT NULL, settlement_id INT DEFAULT NULL, loan_id INT DEFAULT NULL, payment_id INT DEFAULT NULL, expense_id INT DEFAULT NULL, date DATETIME NOT NULL, amount NUMERIC(10, 2) NOT NULL, INDEX IDX_723705D1A76ED395 (user_id), INDEX IDX_723705D120D91DB4 (peer_id), INDEX IDX_723705D1B8BB39DD (from_group_id), INDEX IDX_723705D1C2B9C425 (settlement_id), INDEX IDX_723705D1CE73868F (loan_id), INDEX IDX_723705D14C3A3BB (payment_id), INDEX IDX_723705D1F395DB7B (expense_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, nickname VARCHAR(255) DEFAULT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_group (id INT AUTO_INCREMENT NOT NULL, in_group_id INT NOT NULL, user_id INT NOT NULL, amount NUMERIC(10, 2) NOT NULL, INDEX IDX_8F02BF9DB9ADA51B (in_group_id), INDEX IDX_8F02BF9DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE connection ADD CONSTRAINT FK_29F77366A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE connection ADD CONSTRAINT FK_29F7736620D91DB4 FOREIGN KEY (peer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE expense ADD CONSTRAINT FK_2D3A8DA612469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE expense ADD CONSTRAINT FK_2D3A8DA6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE expense ADD CONSTRAINT FK_2D3A8DA6B9ADA51B FOREIGN KEY (in_group_id) REFERENCES `group` (id)');
        $this->addSql('ALTER TABLE expense ADD CONSTRAINT FK_2D3A8DA620D91DB4 FOREIGN KEY (peer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE loan ADD CONSTRAINT FK_C5D30D03855D3E3D FOREIGN KEY (lender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE loan ADD CONSTRAINT FK_C5D30D0311CE312B FOREIGN KEY (borrower_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE loan ADD CONSTRAINT FK_C5D30D034C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id)');
        $this->addSql('ALTER TABLE loan ADD CONSTRAINT FK_C5D30D03F395DB7B FOREIGN KEY (expense_id) REFERENCES expense (id)');
        $this->addSql('ALTER TABLE loan ADD CONSTRAINT FK_C5D30D0312469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840DF395DB7B FOREIGN KEY (expense_id) REFERENCES expense (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE settlement ADD CONSTRAINT FK_DD9F1B51855D3E3D FOREIGN KEY (lender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE settlement ADD CONSTRAINT FK_DD9F1B51C17AD9A9 FOREIGN KEY (payer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D120D91DB4 FOREIGN KEY (peer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1B8BB39DD FOREIGN KEY (from_group_id) REFERENCES `group` (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1C2B9C425 FOREIGN KEY (settlement_id) REFERENCES settlement (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1CE73868F FOREIGN KEY (loan_id) REFERENCES loan (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D14C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1F395DB7B FOREIGN KEY (expense_id) REFERENCES expense (id)');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9DB9ADA51B FOREIGN KEY (in_group_id) REFERENCES `group` (id)');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE expense DROP FOREIGN KEY FK_2D3A8DA612469DE2');
        $this->addSql('ALTER TABLE loan DROP FOREIGN KEY FK_C5D30D0312469DE2');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D12469DE2');
        $this->addSql('ALTER TABLE loan DROP FOREIGN KEY FK_C5D30D03F395DB7B');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840DF395DB7B');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1F395DB7B');
        $this->addSql('ALTER TABLE expense DROP FOREIGN KEY FK_2D3A8DA6B9ADA51B');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1B8BB39DD');
        $this->addSql('ALTER TABLE user_group DROP FOREIGN KEY FK_8F02BF9DB9ADA51B');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1CE73868F');
        $this->addSql('ALTER TABLE loan DROP FOREIGN KEY FK_C5D30D034C3A3BB');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D14C3A3BB');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1C2B9C425');
        $this->addSql('ALTER TABLE connection DROP FOREIGN KEY FK_29F77366A76ED395');
        $this->addSql('ALTER TABLE connection DROP FOREIGN KEY FK_29F7736620D91DB4');
        $this->addSql('ALTER TABLE expense DROP FOREIGN KEY FK_2D3A8DA6A76ED395');
        $this->addSql('ALTER TABLE expense DROP FOREIGN KEY FK_2D3A8DA620D91DB4');
        $this->addSql('ALTER TABLE loan DROP FOREIGN KEY FK_C5D30D03855D3E3D');
        $this->addSql('ALTER TABLE loan DROP FOREIGN KEY FK_C5D30D0311CE312B');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840DA76ED395');
        $this->addSql('ALTER TABLE settlement DROP FOREIGN KEY FK_DD9F1B51855D3E3D');
        $this->addSql('ALTER TABLE settlement DROP FOREIGN KEY FK_DD9F1B51C17AD9A9');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1A76ED395');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D120D91DB4');
        $this->addSql('ALTER TABLE user_group DROP FOREIGN KEY FK_8F02BF9DA76ED395');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE connection');
        $this->addSql('DROP TABLE expense');
        $this->addSql('DROP TABLE `group`');
        $this->addSql('DROP TABLE loan');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE settlement');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_group');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
