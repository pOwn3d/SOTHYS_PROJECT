<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210504072430 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE intercom (id INT AUTO_INCREMENT NOT NULL, reference VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quote (id INT AUTO_INCREMENT NOT NULL, society_id INT DEFAULT NULL, date_quote DATETIME NOT NULL, reference VARCHAR(255) DEFAULT NULL, INDEX IDX_6B71CBF4E6389D24 (society_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE quote ADD CONSTRAINT FK_6B71CBF4E6389D24 FOREIGN KEY (society_id) REFERENCES society (id)');
        $this->addSql('ALTER TABLE `order` CHANGE id_order id_order INT DEFAULT NULL, CHANGE id_order_x3 id_order_x3 INT DEFAULT NULL, CHANGE date_delivery date_delivery DATETIME DEFAULT NULL, CHANGE id_down_statut id_down_statut INT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_draft CHANGE price price DOUBLE PRECISION DEFAULT NULL, CHANGE price_order price_order DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE order_line CHANGE id_order_x3 id_order_x3 INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE intercom');
        $this->addSql('DROP TABLE quote');
        $this->addSql('ALTER TABLE `order` CHANGE id_order id_order INT NOT NULL, CHANGE id_order_x3 id_order_x3 INT NOT NULL, CHANGE date_delivery date_delivery DATETIME NOT NULL, CHANGE id_down_statut id_down_statut INT NOT NULL');
        $this->addSql('ALTER TABLE order_draft CHANGE price price INT DEFAULT NULL, CHANGE price_order price_order INT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_line CHANGE id_order_x3 id_order_x3 INT NOT NULL');
    }
}
