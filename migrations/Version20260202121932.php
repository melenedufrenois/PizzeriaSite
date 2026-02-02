<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260202121932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart_items (id INT AUTO_INCREMENT NOT NULL, quantity INT NOT NULL, unit_price NUMERIC(8, 2) NOT NULL, created_at DATETIME NOT NULL, cart_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_BEF484451AD5CDBF (cart_id), INDEX IDX_BEF484454584665A (product_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE carts (id INT AUTO_INCREMENT NOT NULL, session_id VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, customer_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_4E004AAC9395C3F3 (customer_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE customers (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, phone VARCHAR(20) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(10) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE ingredients (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, price NUMERIC(8, 2) DEFAULT NULL, is_available TINYINT NOT NULL, image VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE order_items (id INT AUTO_INCREMENT NOT NULL, product_name VARCHAR(150) NOT NULL, quantity INT NOT NULL, unit_price NUMERIC(8, 2) NOT NULL, created_at DATETIME NOT NULL, order_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_62809DB08D9F6D38 (order_id), INDEX IDX_62809DB04584665A (product_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, reference VARCHAR(50) NOT NULL, status VARCHAR(50) NOT NULL, payment_status VARCHAR(50) NOT NULL, total_amount NUMERIC(10, 2) NOT NULL, delivery_fee NUMERIC(8, 2) DEFAULT NULL, delivery_address VARCHAR(255) DEFAULT NULL, delivery_postal_code VARCHAR(10) DEFAULT NULL, delivery_city VARCHAR(100) DEFAULT NULL, customer_name VARCHAR(100) DEFAULT NULL, customer_email VARCHAR(180) DEFAULT NULL, customer_phone VARCHAR(20) DEFAULT NULL, notes LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, delivered_at DATETIME DEFAULT NULL, customer_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_E52FFDEEAEA34913 (reference), INDEX IDX_E52FFDEE9395C3F3 (customer_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE products (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(150) NOT NULL, description LONGTEXT DEFAULT NULL, price NUMERIC(8, 2) NOT NULL, image VARCHAR(255) DEFAULT NULL, is_available TINYINT NOT NULL, is_popular TINYINT NOT NULL, category VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE product_ingredients (product_id INT NOT NULL, ingredient_id INT NOT NULL, INDEX IDX_E74F8F504584665A (product_id), INDEX IDX_E74F8F50933FE08C (ingredient_id), PRIMARY KEY (product_id, ingredient_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE cart_items ADD CONSTRAINT FK_BEF484451AD5CDBF FOREIGN KEY (cart_id) REFERENCES carts (id)');
        $this->addSql('ALTER TABLE cart_items ADD CONSTRAINT FK_BEF484454584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE carts ADD CONSTRAINT FK_4E004AAC9395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id)');
        $this->addSql('ALTER TABLE order_items ADD CONSTRAINT FK_62809DB08D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE order_items ADD CONSTRAINT FK_62809DB04584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE9395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id)');
        $this->addSql('ALTER TABLE product_ingredients ADD CONSTRAINT FK_E74F8F504584665A FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_ingredients ADD CONSTRAINT FK_E74F8F50933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredients (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_items DROP FOREIGN KEY FK_BEF484451AD5CDBF');
        $this->addSql('ALTER TABLE cart_items DROP FOREIGN KEY FK_BEF484454584665A');
        $this->addSql('ALTER TABLE carts DROP FOREIGN KEY FK_4E004AAC9395C3F3');
        $this->addSql('ALTER TABLE order_items DROP FOREIGN KEY FK_62809DB08D9F6D38');
        $this->addSql('ALTER TABLE order_items DROP FOREIGN KEY FK_62809DB04584665A');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE9395C3F3');
        $this->addSql('ALTER TABLE product_ingredients DROP FOREIGN KEY FK_E74F8F504584665A');
        $this->addSql('ALTER TABLE product_ingredients DROP FOREIGN KEY FK_E74F8F50933FE08C');
        $this->addSql('DROP TABLE cart_items');
        $this->addSql('DROP TABLE carts');
        $this->addSql('DROP TABLE customers');
        $this->addSql('DROP TABLE ingredients');
        $this->addSql('DROP TABLE order_items');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE product_ingredients');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
