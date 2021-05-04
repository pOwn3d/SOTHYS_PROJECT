<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210504073514 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer_intercom DROP FOREIGN KEY FK_C66AD0677FD1AE4A');
        $this->addSql('DROP INDEX IDX_C66AD0677FD1AE4A ON customer_intercom');
        $this->addSql('ALTER TABLE customer_intercom CHANGE society_customer_intercom_id intercom_society_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE customer_intercom ADD CONSTRAINT FK_C66AD067EA69D288 FOREIGN KEY (intercom_society_id) REFERENCES society (id)');
        $this->addSql('CREATE INDEX IDX_C66AD067EA69D288 ON customer_intercom (intercom_society_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer_intercom DROP FOREIGN KEY FK_C66AD067EA69D288');
        $this->addSql('DROP INDEX IDX_C66AD067EA69D288 ON customer_intercom');
        $this->addSql('ALTER TABLE customer_intercom CHANGE intercom_society_id society_customer_intercom_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE customer_intercom ADD CONSTRAINT FK_C66AD0677FD1AE4A FOREIGN KEY (society_customer_intercom_id) REFERENCES society (id)');
        $this->addSql('CREATE INDEX IDX_C66AD0677FD1AE4A ON customer_intercom (society_customer_intercom_id)');
    }
}
