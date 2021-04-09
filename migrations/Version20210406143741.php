<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210406143741 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item ADD label_fr VARCHAR(255) DEFAULT NULL, ADD label_en VARCHAR(255) DEFAULT NULL, ADD capacity_fr VARCHAR(255) DEFAULT NULL, ADD capacity_en VARCHAR(255) DEFAULT NULL, ADD id_presentation INT DEFAULT NULL, ADD sector VARCHAR(255) DEFAULT NULL, ADD usage_string VARCHAR(255) DEFAULT NULL, ADD amount_bulking INT DEFAULT NULL, ADD code_ean VARCHAR(255) DEFAULT NULL, ADD id_at_the_rate INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398B5E86723 FOREIGN KEY (society_id_id) REFERENCES society (id)');
        $this->addSql('ALTER TABLE order_line ADD CONSTRAINT FK_9CE58EE155E38587 FOREIGN KEY (item_id_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649B5E86723 FOREIGN KEY (society_id_id) REFERENCES society (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP label_fr, DROP label_en, DROP capacity_fr, DROP capacity_en, DROP id_presentation, DROP sector, DROP usage_string, DROP amount_bulking, DROP code_ean, DROP id_at_the_rate');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398B5E86723');
        $this->addSql('ALTER TABLE order_line DROP FOREIGN KEY FK_9CE58EE155E38587');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649B5E86723');
    }
}
