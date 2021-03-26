<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210324132701 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD society_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398B5E86723 FOREIGN KEY (society_id_id) REFERENCES society (id)');
        $this->addSql('CREATE INDEX IDX_F5299398B5E86723 ON `order` (society_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398B5E86723');
        $this->addSql('DROP INDEX IDX_F5299398B5E86723 ON `order`');
        $this->addSql('ALTER TABLE `order` DROP society_id_id');
    }
}
