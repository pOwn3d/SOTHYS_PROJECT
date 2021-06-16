<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210616122747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE generic_name (id INT AUTO_INCREMENT NOT NULL, name_fr VARCHAR(255) NOT NULL, name_en VARCHAR(255) NOT NULL, item_id VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item ADD generic_name_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251ECC59BE87 FOREIGN KEY (generic_name_id) REFERENCES generic_name (id)');
        $this->addSql('CREATE INDEX IDX_1F1B251ECC59BE87 ON item (generic_name_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251ECC59BE87');
        $this->addSql('DROP TABLE generic_name');
        $this->addSql('DROP INDEX IDX_1F1B251ECC59BE87 ON item');
        $this->addSql('ALTER TABLE item DROP generic_name_id');
    }
}
