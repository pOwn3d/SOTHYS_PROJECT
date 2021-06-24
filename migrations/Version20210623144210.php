<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210623144210 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE free_restocking_rules ADD type_of_rule VARCHAR(255) NOT NULL, ADD value_condition VARCHAR(255) NOT NULL, ADD obtention VARCHAR(255) NOT NULL, DROP attribution_condition, DROP obtaining, CHANGE label_fr label_fr VARCHAR(255) DEFAULT NULL, CHANGE label_en label_en VARCHAR(255) DEFAULT NULL, CHANGE value_rules value_rule INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE free_restocking_rules ADD attribution_condition VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD obtaining VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP type_of_rule, DROP value_condition, DROP obtention, CHANGE label_fr label_fr VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE label_en label_en VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE value_rule value_rules INT NOT NULL');
    }
}
