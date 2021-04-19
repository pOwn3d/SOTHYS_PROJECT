<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210413071814 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item_price (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, id_item_id INT DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, date_start_validity DATETIME DEFAULT NULL, date_end_validity DATETIME DEFAULT NULL, price_public DOUBLE PRECISION DEFAULT NULL, price_aesthetic DOUBLE PRECISION DEFAULT NULL, INDEX IDX_E06F390979F37AE5 (id_user_id), INDEX IDX_E06F3909CCF2FB2E (id_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item_price ADD CONSTRAINT FK_E06F390979F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE item_price ADD CONSTRAINT FK_E06F3909CCF2FB2E FOREIGN KEY (id_item_id) REFERENCES item (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE item_price');
    }
}
