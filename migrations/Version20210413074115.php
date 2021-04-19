<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210413074115 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item_price DROP FOREIGN KEY FK_E06F390979F37AE5');
        $this->addSql('DROP INDEX IDX_E06F390979F37AE5 ON item_price');
        $this->addSql('ALTER TABLE item_price CHANGE id_user_id id_society_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE item_price ADD CONSTRAINT FK_E06F390943B21DF3 FOREIGN KEY (id_society_id) REFERENCES society (id)');
        $this->addSql('CREATE INDEX IDX_E06F390943B21DF3 ON item_price (id_society_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item_price DROP FOREIGN KEY FK_E06F390943B21DF3');
        $this->addSql('DROP INDEX IDX_E06F390943B21DF3 ON item_price');
        $this->addSql('ALTER TABLE item_price CHANGE id_society_id id_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE item_price ADD CONSTRAINT FK_E06F390979F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_E06F390979F37AE5 ON item_price (id_user_id)');
    }
}
