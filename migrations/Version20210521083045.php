<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210521083045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE promotion_promotion_item (promotion_id INT NOT NULL, promotion_item_id INT NOT NULL, INDEX IDX_69C2AEAA139DF194 (promotion_id), INDEX IDX_69C2AEAA4A12A464 (promotion_item_id), PRIMARY KEY(promotion_id, promotion_item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE promotion_promotion_item ADD CONSTRAINT FK_69C2AEAA139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promotion_promotion_item ADD CONSTRAINT FK_69C2AEAA4A12A464 FOREIGN KEY (promotion_item_id) REFERENCES promotion_item (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE promotion_promotion_item');
    }
}
