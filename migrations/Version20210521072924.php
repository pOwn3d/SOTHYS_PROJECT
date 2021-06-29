<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210521072924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customer_incoterm (id INT AUTO_INCREMENT NOT NULL, reference_id INT DEFAULT NULL, society_customer_incoterm_id INT DEFAULT NULL, mode_transport_id INT DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, INDEX IDX_4C83B5971645DEA9 (reference_id), INDEX IDX_4C83B597B49182E (society_customer_incoterm_id), INDEX IDX_4C83B5971305CBCC (mode_transport_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gamme_product (id INT AUTO_INCREMENT NOT NULL, label_fr VARCHAR(255) DEFAULT NULL, label_en VARCHAR(255) DEFAULT NULL, ref_id VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_A48E104E21B741A9 (ref_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE incoterm (id INT AUTO_INCREMENT NOT NULL, reference VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, gamme_id INT DEFAULT NULL, gamme_string VARCHAR(255) DEFAULT NULL, label_fr VARCHAR(255) DEFAULT NULL, label_en VARCHAR(255) DEFAULT NULL, capacity_fr VARCHAR(255) DEFAULT NULL, capacity_en VARCHAR(255) DEFAULT NULL, id_presentation VARCHAR(255) DEFAULT NULL, sector VARCHAR(255) DEFAULT NULL, usage_string VARCHAR(255) DEFAULT NULL, amount_bulking INT DEFAULT NULL, code_ean VARCHAR(255) DEFAULT NULL, id_at_the_rate INT DEFAULT NULL, item_id VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1F1B251E126F525E (item_id), INDEX IDX_1F1B251ED2FD85F1 (gamme_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_price (id INT AUTO_INCREMENT NOT NULL, id_item_id INT DEFAULT NULL, id_society_id INT DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, date_start_validity DATETIME DEFAULT NULL, date_end_validity DATETIME DEFAULT NULL, price_public DOUBLE PRECISION DEFAULT NULL, price_aesthetic DOUBLE PRECISION DEFAULT NULL, INDEX IDX_E06F3909CCF2FB2E (id_item_id), INDEX IDX_E06F390943B21DF3 (id_society_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_quantity (id INT AUTO_INCREMENT NOT NULL, id_society_id INT DEFAULT NULL, id_item_id INT DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_F3FEDEFD43B21DF3 (id_society_id), INDEX IDX_F3FEDEFDCCF2FB2E (id_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, society_id_id INT DEFAULT NULL, incoterm_id INT DEFAULT NULL, id_order INT DEFAULT NULL, id_order_x3 INT DEFAULT NULL, date_order DATETIME NOT NULL, date_delivery DATETIME DEFAULT NULL, id_statut INT NOT NULL, id_down_statut INT DEFAULT NULL, reference VARCHAR(255) DEFAULT NULL, date_last_delivery DATETIME DEFAULT NULL, price_order DOUBLE PRECISION DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_F52993987B9266EA (id_order_x3), INDEX IDX_F5299398B5E86723 (society_id_id), INDEX IDX_F52993987055C866 (incoterm_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_draft (id INT AUTO_INCREMENT NOT NULL, id_society_id INT DEFAULT NULL, id_item_id INT DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, state TINYINT(1) DEFAULT NULL, quantity INT NOT NULL, quantity_bundling INT NOT NULL, price_order DOUBLE PRECISION DEFAULT NULL, INDEX IDX_C5E2E26943B21DF3 (id_society_id), INDEX IDX_C5E2E269CCF2FB2E (id_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_line (id INT AUTO_INCREMENT NOT NULL, item_id_id INT DEFAULT NULL, order_draft_id_id INT DEFAULT NULL, id_order INT DEFAULT NULL, id_order_line INT DEFAULT NULL, quantity INT NOT NULL, price VARCHAR(255) DEFAULT NULL, price_unit VARCHAR(255) DEFAULT NULL, remaining_qty_order INT DEFAULT NULL, id_order_x3 INT DEFAULT NULL, INDEX IDX_9CE58EE155E38587 (item_id_id), INDEX IDX_9CE58EE1638165FE (order_draft_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plv (id INT AUTO_INCREMENT NOT NULL, label_fr VARCHAR(255) DEFAULT NULL, label_en VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion (id INT AUTO_INCREMENT NOT NULL, name_fr VARCHAR(255) DEFAULT NULL, name_en VARCHAR(255) DEFAULT NULL, date_start DATETIME NOT NULL, date_end DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion_society (promotion_id INT NOT NULL, society_id INT NOT NULL, INDEX IDX_551997C8139DF194 (promotion_id), INDEX IDX_551997C8E6389D24 (society_id), PRIMARY KEY(promotion_id, society_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion_plv (promotion_id INT NOT NULL, plv_id INT NOT NULL, INDEX IDX_473BE3DF139DF194 (promotion_id), INDEX IDX_473BE3DF3176D54C (plv_id), PRIMARY KEY(promotion_id, plv_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE society (id INT AUTO_INCREMENT NOT NULL, id_customer INT NOT NULL, name VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_D6461F2D1E2629C (id_customer), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transport_mode (id INT AUTO_INCREMENT NOT NULL, id_transport INT NOT NULL, name_fr VARCHAR(255) DEFAULT NULL, name_en VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, society_id_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, account_activated TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649B5E86723 (society_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customer_incoterm ADD CONSTRAINT FK_4C83B5971645DEA9 FOREIGN KEY (reference_id) REFERENCES incoterm (id)');
        $this->addSql('ALTER TABLE customer_incoterm ADD CONSTRAINT FK_4C83B597B49182E FOREIGN KEY (society_customer_incoterm_id) REFERENCES society (id)');
        $this->addSql('ALTER TABLE customer_incoterm ADD CONSTRAINT FK_4C83B5971305CBCC FOREIGN KEY (mode_transport_id) REFERENCES transport_mode (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251ED2FD85F1 FOREIGN KEY (gamme_id) REFERENCES gamme_product (id)');
        $this->addSql('ALTER TABLE item_price ADD CONSTRAINT FK_E06F3909CCF2FB2E FOREIGN KEY (id_item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE item_price ADD CONSTRAINT FK_E06F390943B21DF3 FOREIGN KEY (id_society_id) REFERENCES society (id)');
        $this->addSql('ALTER TABLE item_quantity ADD CONSTRAINT FK_F3FEDEFD43B21DF3 FOREIGN KEY (id_society_id) REFERENCES society (id)');
        $this->addSql('ALTER TABLE item_quantity ADD CONSTRAINT FK_F3FEDEFDCCF2FB2E FOREIGN KEY (id_item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398B5E86723 FOREIGN KEY (society_id_id) REFERENCES society (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993987055C866 FOREIGN KEY (incoterm_id) REFERENCES customer_incoterm (id)');
        $this->addSql('ALTER TABLE order_draft ADD CONSTRAINT FK_C5E2E26943B21DF3 FOREIGN KEY (id_society_id) REFERENCES society (id)');
        $this->addSql('ALTER TABLE order_draft ADD CONSTRAINT FK_C5E2E269CCF2FB2E FOREIGN KEY (id_item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE order_line ADD CONSTRAINT FK_9CE58EE155E38587 FOREIGN KEY (item_id_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE order_line ADD CONSTRAINT FK_9CE58EE1638165FE FOREIGN KEY (order_draft_id_id) REFERENCES order_draft (id)');
        $this->addSql('ALTER TABLE promotion_society ADD CONSTRAINT FK_551997C8139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promotion_society ADD CONSTRAINT FK_551997C8E6389D24 FOREIGN KEY (society_id) REFERENCES society (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promotion_plv ADD CONSTRAINT FK_473BE3DF139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promotion_plv ADD CONSTRAINT FK_473BE3DF3176D54C FOREIGN KEY (plv_id) REFERENCES plv (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649B5E86723 FOREIGN KEY (society_id_id) REFERENCES society (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993987055C866');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251ED2FD85F1');
        $this->addSql('ALTER TABLE customer_incoterm DROP FOREIGN KEY FK_4C83B5971645DEA9');
        $this->addSql('ALTER TABLE item_price DROP FOREIGN KEY FK_E06F3909CCF2FB2E');
        $this->addSql('ALTER TABLE item_quantity DROP FOREIGN KEY FK_F3FEDEFDCCF2FB2E');
        $this->addSql('ALTER TABLE order_draft DROP FOREIGN KEY FK_C5E2E269CCF2FB2E');
        $this->addSql('ALTER TABLE order_line DROP FOREIGN KEY FK_9CE58EE155E38587');
        $this->addSql('ALTER TABLE order_line DROP FOREIGN KEY FK_9CE58EE1638165FE');
        $this->addSql('ALTER TABLE promotion_plv DROP FOREIGN KEY FK_473BE3DF3176D54C');
        $this->addSql('ALTER TABLE promotion_society DROP FOREIGN KEY FK_551997C8139DF194');
        $this->addSql('ALTER TABLE promotion_plv DROP FOREIGN KEY FK_473BE3DF139DF194');
        $this->addSql('ALTER TABLE customer_incoterm DROP FOREIGN KEY FK_4C83B597B49182E');
        $this->addSql('ALTER TABLE item_price DROP FOREIGN KEY FK_E06F390943B21DF3');
        $this->addSql('ALTER TABLE item_quantity DROP FOREIGN KEY FK_F3FEDEFD43B21DF3');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398B5E86723');
        $this->addSql('ALTER TABLE order_draft DROP FOREIGN KEY FK_C5E2E26943B21DF3');
        $this->addSql('ALTER TABLE promotion_society DROP FOREIGN KEY FK_551997C8E6389D24');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649B5E86723');
        $this->addSql('ALTER TABLE customer_incoterm DROP FOREIGN KEY FK_4C83B5971305CBCC');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE customer_incoterm');
        $this->addSql('DROP TABLE gamme_product');
        $this->addSql('DROP TABLE incoterm');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE item_price');
        $this->addSql('DROP TABLE item_quantity');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_draft');
        $this->addSql('DROP TABLE order_line');
        $this->addSql('DROP TABLE plv');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE promotion_society');
        $this->addSql('DROP TABLE promotion_plv');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE society');
        $this->addSql('DROP TABLE transport_mode');
        $this->addSql('DROP TABLE user');
    }
}
