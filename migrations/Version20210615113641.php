<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210615113641 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payment_method (id INT AUTO_INCREMENT NOT NULL, id_x3 VARCHAR(255) NOT NULL, label_fr VARCHAR(255) NOT NULL, label_en VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE society ADD payment_method_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE society ADD CONSTRAINT FK_D6461F25AA1164F FOREIGN KEY (payment_method_id) REFERENCES payment_method (id)');
        $this->addSql('CREATE INDEX IDX_D6461F25AA1164F ON society (payment_method_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE society DROP FOREIGN KEY FK_D6461F25AA1164F');
        $this->addSql('DROP TABLE payment_method');
        $this->addSql('DROP INDEX IDX_D6461F25AA1164F ON society');
        $this->addSql('ALTER TABLE society DROP payment_method_id');
    }
}
