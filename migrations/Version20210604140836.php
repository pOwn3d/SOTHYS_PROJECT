<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210604140836 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_draft ADD promotion_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_draft ADD CONSTRAINT FK_C5E2E2691F42EA0A FOREIGN KEY (promotion_id_id) REFERENCES promotion (id)');
        $this->addSql('CREATE INDEX IDX_C5E2E2691F42EA0A ON order_draft (promotion_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_draft DROP FOREIGN KEY FK_C5E2E2691F42EA0A');
        $this->addSql('DROP INDEX IDX_C5E2E2691F42EA0A ON order_draft');
        $this->addSql('ALTER TABLE order_draft DROP promotion_id_id');
    }
}
