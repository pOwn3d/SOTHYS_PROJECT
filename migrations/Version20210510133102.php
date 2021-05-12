<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210510133102 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer_incoterm ADD mode_transport_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE customer_incoterm ADD CONSTRAINT FK_4C83B5971305CBCC FOREIGN KEY (mode_transport_id) REFERENCES transport_mode (id)');
        $this->addSql('CREATE INDEX IDX_4C83B5971305CBCC ON customer_incoterm (mode_transport_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer_incoterm DROP FOREIGN KEY FK_4C83B5971305CBCC');
        $this->addSql('DROP INDEX IDX_4C83B5971305CBCC ON customer_incoterm');
        $this->addSql('ALTER TABLE customer_incoterm DROP mode_transport_id');
    }
}
