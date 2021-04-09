<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210409075900 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE gamme_product (id INT AUTO_INCREMENT NOT NULL, label_fr VARCHAR(255) DEFAULT NULL, label_en VARCHAR(255) DEFAULT NULL, ref_id VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_A48E104E21B741A9 (ref_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, gamme_id INT DEFAULT NULL, gamme_string VARCHAR(255) DEFAULT NULL, label_fr VARCHAR(255) DEFAULT NULL, label_en VARCHAR(255) DEFAULT NULL, capacity_fr VARCHAR(255) DEFAULT NULL, capacity_en VARCHAR(255) DEFAULT NULL, id_presentation VARCHAR(255) DEFAULT NULL, sector VARCHAR(255) DEFAULT NULL, usage_string VARCHAR(255) DEFAULT NULL, amount_bulking INT DEFAULT NULL, code_ean VARCHAR(255) DEFAULT NULL, id_at_the_rate INT DEFAULT NULL, item_id VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1F1B251E126F525E (item_id), INDEX IDX_1F1B251ED2FD85F1 (gamme_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, society_id_id INT DEFAULT NULL, id_order INT NOT NULL, id_order_x3 INT NOT NULL, date_order DATETIME NOT NULL, date_delivery DATETIME NOT NULL, id_statut INT NOT NULL, id_down_statut INT NOT NULL, reference VARCHAR(255) DEFAULT NULL, date_last_delivery DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_F52993987B9266EA (id_order_x3), INDEX IDX_F5299398B5E86723 (society_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_line (id INT AUTO_INCREMENT NOT NULL, item_id_id INT DEFAULT NULL, id_order INT DEFAULT NULL, id_order_line INT DEFAULT NULL, quantity INT NOT NULL, price VARCHAR(255) DEFAULT NULL, price_unit VARCHAR(255) DEFAULT NULL, remaining_qty_order INT DEFAULT NULL, id_order_x3 INT NOT NULL, INDEX IDX_9CE58EE155E38587 (item_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE society (id INT AUTO_INCREMENT NOT NULL, id_customer INT NOT NULL, name VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_D6461F2D1E2629C (id_customer), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, society_id_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, account_activated TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649B5E86723 (society_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251ED2FD85F1 FOREIGN KEY (gamme_id) REFERENCES gamme_product (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398B5E86723 FOREIGN KEY (society_id_id) REFERENCES society (id)');
        $this->addSql('ALTER TABLE order_line ADD CONSTRAINT FK_9CE58EE155E38587 FOREIGN KEY (item_id_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649B5E86723 FOREIGN KEY (society_id_id) REFERENCES society (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251ED2FD85F1');
        $this->addSql('ALTER TABLE order_line DROP FOREIGN KEY FK_9CE58EE155E38587');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398B5E86723');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649B5E86723');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE gamme_product');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_line');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE society');
        $this->addSql('DROP TABLE user');
    }
}
