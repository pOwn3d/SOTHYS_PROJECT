<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210413134341 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item_quantity (id INT AUTO_INCREMENT NOT NULL, id_society_id INT DEFAULT NULL, id_item_id INT DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_F3FEDEFD43B21DF3 (id_society_id), INDEX IDX_F3FEDEFDCCF2FB2E (id_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item_quantity ADD CONSTRAINT FK_F3FEDEFD43B21DF3 FOREIGN KEY (id_society_id) REFERENCES society (id)');
        $this->addSql('ALTER TABLE item_quantity ADD CONSTRAINT FK_F3FEDEFDCCF2FB2E FOREIGN KEY (id_item_id) REFERENCES item (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE item_quantity');
    }
}
