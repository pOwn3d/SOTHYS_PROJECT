<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210406150141 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item ADD gamme_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251ED2FD85F1 FOREIGN KEY (gamme_id) REFERENCES gamme_product (id)');
        $this->addSql('CREATE INDEX IDX_1F1B251ED2FD85F1 ON item (gamme_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251ED2FD85F1');
        $this->addSql('DROP INDEX IDX_1F1B251ED2FD85F1 ON item');
        $this->addSql('ALTER TABLE item DROP gamme_id');
    }
}
