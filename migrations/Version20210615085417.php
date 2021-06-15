<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210615085417 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD transport_mode_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398E33245BB FOREIGN KEY (transport_mode_id) REFERENCES transport_mode (id)');
        $this->addSql('CREATE INDEX IDX_F5299398E33245BB ON `order` (transport_mode_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398E33245BB');
        $this->addSql('DROP INDEX IDX_F5299398E33245BB ON `order`');
        $this->addSql('ALTER TABLE `order` DROP transport_mode_id');
    }
}
