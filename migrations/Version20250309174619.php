<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250309174619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity_log ADD user_id INT NOT NULL, ADD action_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE activity_log ADD CONSTRAINT FK_FD06F647A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE activity_log ADD CONSTRAINT FK_FD06F6471FEE0472 FOREIGN KEY (action_type_id) REFERENCES action_type (id)');
        $this->addSql('CREATE INDEX IDX_FD06F647A76ED395 ON activity_log (user_id)');
        $this->addSql('CREATE INDEX IDX_FD06F6471FEE0472 ON activity_log (action_type_id)');
        $this->addSql('ALTER TABLE cart CHANGE status status TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE item ADD cart_id INT DEFAULT NULL, ADD order_id INT DEFAULT NULL, CHANGE type type VARCHAR(255) NOT NULL, CHANGE reference_id product_id INT NOT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E1AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E8D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_1F1B251E4584665A ON item (product_id)');
        $this->addSql('CREATE INDEX IDX_1F1B251E1AD5CDBF ON item (cart_id)');
        $this->addSql('CREATE INDEX IDX_1F1B251E8D9F6D38 ON item (order_id)');
        $this->addSql('ALTER TABLE `order` CHANGE status status VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE order_history ADD order_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_history ADD CONSTRAINT FK_D1C0D9008D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_D1C0D9008D9F6D38 ON order_history (order_id)');
        $this->addSql('ALTER TABLE payment ADD order_id INT NOT NULL');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D8D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6D28840D8D9F6D38 ON payment (order_id)');
        $this->addSql('ALTER TABLE product ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD12469DE2 ON product (category_id)');
        $this->addSql('ALTER TABLE statistics ADD cron_stats_id INT NOT NULL');
        $this->addSql('ALTER TABLE statistics ADD CONSTRAINT FK_E2D38B22C05DDC08 FOREIGN KEY (cron_stats_id) REFERENCES cronstats (id)');
        $this->addSql('CREATE INDEX IDX_E2D38B22C05DDC08 ON statistics (cron_stats_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart CHANGE status status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D8D9F6D38');
        $this->addSql('DROP INDEX UNIQ_6D28840D8D9F6D38 ON payment');
        $this->addSql('ALTER TABLE payment DROP order_id');
        $this->addSql('ALTER TABLE activity_log DROP FOREIGN KEY FK_FD06F647A76ED395');
        $this->addSql('ALTER TABLE activity_log DROP FOREIGN KEY FK_FD06F6471FEE0472');
        $this->addSql('DROP INDEX IDX_FD06F647A76ED395 ON activity_log');
        $this->addSql('DROP INDEX IDX_FD06F6471FEE0472 ON activity_log');
        $this->addSql('ALTER TABLE activity_log DROP user_id, DROP action_type_id');
        $this->addSql('ALTER TABLE statistics DROP FOREIGN KEY FK_E2D38B22C05DDC08');
        $this->addSql('DROP INDEX IDX_E2D38B22C05DDC08 ON statistics');
        $this->addSql('ALTER TABLE statistics DROP cron_stats_id');
        $this->addSql('ALTER TABLE order_history DROP FOREIGN KEY FK_D1C0D9008D9F6D38');
        $this->addSql('DROP INDEX IDX_D1C0D9008D9F6D38 ON order_history');
        $this->addSql('ALTER TABLE order_history DROP order_id');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('DROP INDEX IDX_D34A04AD12469DE2 ON product');
        $this->addSql('ALTER TABLE product DROP category_id');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E4584665A');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E1AD5CDBF');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E8D9F6D38');
        $this->addSql('DROP INDEX IDX_1F1B251E4584665A ON item');
        $this->addSql('DROP INDEX IDX_1F1B251E1AD5CDBF ON item');
        $this->addSql('DROP INDEX IDX_1F1B251E8D9F6D38 ON item');
        $this->addSql('ALTER TABLE item DROP cart_id, DROP order_id, CHANGE type type TINYINT(1) NOT NULL, CHANGE product_id reference_id INT NOT NULL');
        $this->addSql('ALTER TABLE `order` CHANGE status status VARCHAR(255) NOT NULL');
    }
}
