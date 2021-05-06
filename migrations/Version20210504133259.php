<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210504133259 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer_incoterm ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE customer_incoterm ADD CONSTRAINT FK_4C83B597A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_4C83B597A76ED395 ON customer_incoterm (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer_incoterm DROP FOREIGN KEY FK_4C83B597A76ED395');
        $this->addSql('DROP INDEX IDX_4C83B597A76ED395 ON customer_incoterm');
        $this->addSql('ALTER TABLE customer_incoterm DROP user_id');
    }
}
