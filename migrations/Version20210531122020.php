<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210531122020 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE promotion_free_rules (promotion_id INT NOT NULL, free_rules_id INT NOT NULL, INDEX IDX_576DC528139DF194 (promotion_id), INDEX IDX_576DC5284CFE6AAE (free_rules_id), PRIMARY KEY(promotion_id, free_rules_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE promotion_free_rules ADD CONSTRAINT FK_576DC528139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promotion_free_rules ADD CONSTRAINT FK_576DC5284CFE6AAE FOREIGN KEY (free_rules_id) REFERENCES free_rules (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE promotion_free_rules');
    }
}
