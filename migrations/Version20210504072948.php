<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210504072948 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customer_intercom (id INT AUTO_INCREMENT NOT NULL, society_id INT DEFAULT NULL, reference_id INT DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, INDEX IDX_C66AD067E6389D24 (society_id), INDEX IDX_C66AD0671645DEA9 (reference_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customer_intercom ADD CONSTRAINT FK_C66AD067E6389D24 FOREIGN KEY (society_id) REFERENCES society (id)');
        $this->addSql('ALTER TABLE customer_intercom ADD CONSTRAINT FK_C66AD0671645DEA9 FOREIGN KEY (reference_id) REFERENCES intercom (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE customer_intercom');
    }
}
