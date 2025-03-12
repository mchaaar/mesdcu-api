<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250312125735 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE action_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activity_log (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, action_type_id INT NOT NULL, action_date DATETIME NOT NULL, details LONGTEXT NOT NULL, ip_address VARCHAR(45) DEFAULT NULL, user_agent LONGTEXT DEFAULT NULL, INDEX IDX_FD06F647A76ED395 (user_id), INDEX IDX_FD06F6471FEE0472 (action_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL, status TINYINT(1) NOT NULL, INDEX IDX_BA388B7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, is_active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cronstats (id INT AUTO_INCREMENT NOT NULL, stat_type VARCHAR(255) NOT NULL, last_execution DATETIME NOT NULL, next_execution DATETIME NOT NULL, status VARCHAR(50) NOT NULL, frequency VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, cart_id INT DEFAULT NULL, order_id INT DEFAULT NULL, quantity INT NOT NULL, unit_price NUMERIC(10, 2) NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_1F1B251E4584665A (product_id), INDEX IDX_1F1B251E1AD5CDBF (cart_id), INDEX IDX_1F1B251E8D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, order_date DATETIME NOT NULL, total_amount NUMERIC(10, 2) NOT NULL, status VARCHAR(50) NOT NULL, INDEX IDX_F5299398A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_history (id INT AUTO_INCREMENT NOT NULL, order_id INT NOT NULL, modification_date DATETIME NOT NULL, previous_status VARCHAR(255) NOT NULL, new_status VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_D1C0D9008D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, order_id INT NOT NULL, amount NUMERIC(10, 2) NOT NULL, payment_date DATETIME NOT NULL, payment_method VARCHAR(50) NOT NULL, transaction_reference VARCHAR(100) NOT NULL, payment_status VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_6D28840D8D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, price NUMERIC(10, 2) NOT NULL, stock INT NOT NULL, image_url VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) NOT NULL, INDEX IDX_D34A04AD12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statistics (id INT AUTO_INCREMENT NOT NULL, cron_stats_id INT NOT NULL, stat_type VARCHAR(255) NOT NULL, calculated_at DATETIME NOT NULL, value NUMERIC(10, 2) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_E2D38B22C05DDC08 (cron_stats_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, phone VARCHAR(20) DEFAULT NULL, registration_date DATETIME NOT NULL, is_active TINYINT(1) NOT NULL, roles JSON NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity_log ADD CONSTRAINT FK_FD06F647A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE activity_log ADD CONSTRAINT FK_FD06F6471FEE0472 FOREIGN KEY (action_type_id) REFERENCES action_type (id)');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E1AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E8D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE order_history ADD CONSTRAINT FK_D1C0D9008D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D8D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE statistics ADD CONSTRAINT FK_E2D38B22C05DDC08 FOREIGN KEY (cron_stats_id) REFERENCES cronstats (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity_log DROP FOREIGN KEY FK_FD06F647A76ED395');
        $this->addSql('ALTER TABLE activity_log DROP FOREIGN KEY FK_FD06F6471FEE0472');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7A76ED395');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E4584665A');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E1AD5CDBF');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E8D9F6D38');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395');
        $this->addSql('ALTER TABLE order_history DROP FOREIGN KEY FK_D1C0D9008D9F6D38');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D8D9F6D38');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE statistics DROP FOREIGN KEY FK_E2D38B22C05DDC08');
        $this->addSql('DROP TABLE action_type');
        $this->addSql('DROP TABLE activity_log');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE cronstats');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_history');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE statistics');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
