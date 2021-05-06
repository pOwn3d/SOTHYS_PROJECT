<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210504124604 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer_incoterm ADD CONSTRAINT FK_4C83B5971645DEA9 FOREIGN KEY (reference_id) REFERENCES incoterm (id)');
        $this->addSql('ALTER TABLE customer_incoterm ADD CONSTRAINT FK_4C83B597B49182E FOREIGN KEY (society_customer_incoterm_id) REFERENCES society (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251ED2FD85F1 FOREIGN KEY (gamme_id) REFERENCES gamme_product (id)');
        $this->addSql('ALTER TABLE item_price ADD CONSTRAINT FK_E06F3909CCF2FB2E FOREIGN KEY (id_item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE item_price ADD CONSTRAINT FK_E06F390943B21DF3 FOREIGN KEY (id_society_id) REFERENCES society (id)');
        $this->addSql('ALTER TABLE item_quantity ADD CONSTRAINT FK_F3FEDEFD43B21DF3 FOREIGN KEY (id_society_id) REFERENCES society (id)');
        $this->addSql('ALTER TABLE item_quantity ADD CONSTRAINT FK_F3FEDEFDCCF2FB2E FOREIGN KEY (id_item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE `order` ADD incoterm_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398B5E86723 FOREIGN KEY (society_id_id) REFERENCES society (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993987055C866 FOREIGN KEY (incoterm_id) REFERENCES customer_incoterm (id)');
        $this->addSql('CREATE INDEX IDX_F52993987055C866 ON `order` (incoterm_id)');
        $this->addSql('ALTER TABLE order_draft ADD CONSTRAINT FK_C5E2E26943B21DF3 FOREIGN KEY (id_society_id) REFERENCES society (id)');
        $this->addSql('ALTER TABLE order_draft ADD CONSTRAINT FK_C5E2E269CCF2FB2E FOREIGN KEY (id_item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE order_line ADD CONSTRAINT FK_9CE58EE155E38587 FOREIGN KEY (item_id_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE quote ADD CONSTRAINT FK_6B71CBF4E6389D24 FOREIGN KEY (society_id) REFERENCES society (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649B5E86723 FOREIGN KEY (society_id_id) REFERENCES society (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer_incoterm DROP FOREIGN KEY FK_4C83B5971645DEA9');
        $this->addSql('ALTER TABLE customer_incoterm DROP FOREIGN KEY FK_4C83B597B49182E');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251ED2FD85F1');
        $this->addSql('ALTER TABLE item_price DROP FOREIGN KEY FK_E06F3909CCF2FB2E');
        $this->addSql('ALTER TABLE item_price DROP FOREIGN KEY FK_E06F390943B21DF3');
        $this->addSql('ALTER TABLE item_quantity DROP FOREIGN KEY FK_F3FEDEFD43B21DF3');
        $this->addSql('ALTER TABLE item_quantity DROP FOREIGN KEY FK_F3FEDEFDCCF2FB2E');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398B5E86723');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993987055C866');
        $this->addSql('DROP INDEX IDX_F52993987055C866 ON `order`');
        $this->addSql('ALTER TABLE `order` DROP incoterm_id');
        $this->addSql('ALTER TABLE order_draft DROP FOREIGN KEY FK_C5E2E26943B21DF3');
        $this->addSql('ALTER TABLE order_draft DROP FOREIGN KEY FK_C5E2E269CCF2FB2E');
        $this->addSql('ALTER TABLE order_line DROP FOREIGN KEY FK_9CE58EE155E38587');
        $this->addSql('ALTER TABLE quote DROP FOREIGN KEY FK_6B71CBF4E6389D24');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649B5E86723');
    }
}
