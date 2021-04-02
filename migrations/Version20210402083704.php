<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210402083704 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_line ADD item_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_line ADD CONSTRAINT FK_9CE58EE155E38587 FOREIGN KEY (item_id_id) REFERENCES item (id)');
        $this->addSql('CREATE INDEX IDX_9CE58EE155E38587 ON order_line (item_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_line DROP FOREIGN KEY FK_9CE58EE155E38587');
        $this->addSql('DROP INDEX IDX_9CE58EE155E38587 ON order_line');
        $this->addSql('ALTER TABLE order_line DROP item_id_id');
    }
}
