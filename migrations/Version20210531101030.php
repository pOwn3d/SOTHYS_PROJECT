<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210531101030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE free_rules (id INT AUTO_INCREMENT NOT NULL, id_item_purchased_id INT DEFAULT NULL, id_item_free_id INT DEFAULT NULL, qty_purchased INT DEFAULT NULL, qty_free INT DEFAULT NULL, amount_purchased_min INT DEFAULT NULL, amount_purchased_max INT DEFAULT NULL, amount_free INT DEFAULT NULL, INDEX IDX_12C3EE8CA63AC343 (id_item_purchased_id), INDEX IDX_12C3EE8CDF56BA63 (id_item_free_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE free_rules ADD CONSTRAINT FK_12C3EE8CA63AC343 FOREIGN KEY (id_item_purchased_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE free_rules ADD CONSTRAINT FK_12C3EE8CDF56BA63 FOREIGN KEY (id_item_free_id) REFERENCES item (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE free_rules');
    }
}
