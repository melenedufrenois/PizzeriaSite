<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260330082340 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE app_config (id INT AUTO_INCREMENT NOT NULL, config_key VARCHAR(100) NOT NULL, config_value VARCHAR(255) NOT NULL, UNIQUE INDEX uniq_app_config_key (config_key), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE dessert (ingredients JSON NOT NULL, contains_allergens TINYINT NOT NULL, id INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE drink (volume VARCHAR(20) DEFAULT NULL, is_alcoholic TINYINT NOT NULL, id INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE pasta (ingredients JSON NOT NULL, pasta_type VARCHAR(50) DEFAULT NULL, id INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, image VARCHAR(255) NOT NULL, price NUMERIC(5, 2) NOT NULL, type VARCHAR(50) DEFAULT NULL, popular TINYINT NOT NULL, active TINYINT NOT NULL, display_order INT DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL, allergens JSON NOT NULL, dtype VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE dessert ADD CONSTRAINT FK_79291B96BF396750 FOREIGN KEY (id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE drink ADD CONSTRAINT FK_DBE40D1BF396750 FOREIGN KEY (id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pasta ADD CONSTRAINT FK_9B3BBC81BF396750 FOREIGN KEY (id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cart_items DROP FOREIGN KEY `FK_BEF484451AD5CDBF`');
        $this->addSql('ALTER TABLE cart_items DROP FOREIGN KEY `FK_BEF484454584665A`');
        $this->addSql('ALTER TABLE carts DROP FOREIGN KEY `FK_4E004AAC9395C3F3`');
        $this->addSql('ALTER TABLE order_items DROP FOREIGN KEY `FK_62809DB04584665A`');
        $this->addSql('ALTER TABLE order_items DROP FOREIGN KEY `FK_62809DB08D9F6D38`');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY `FK_E52FFDEE9395C3F3`');
        $this->addSql('ALTER TABLE product_ingredients DROP FOREIGN KEY `FK_E74F8F504584665A`');
        $this->addSql('ALTER TABLE product_ingredients DROP FOREIGN KEY `FK_E74F8F50933FE08C`');
        $this->addSql('DROP TABLE cart_items');
        $this->addSql('DROP TABLE carts');
        $this->addSql('DROP TABLE customers');
        $this->addSql('DROP TABLE ingredients');
        $this->addSql('DROP TABLE order_items');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE product_ingredients');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP INDEX id ON pizza');
        $this->addSql('ALTER TABLE pizza DROP name, DROP image, DROP price, DROP type, DROP popular, DROP active, DROP created_at, CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE pizza ADD CONSTRAINT FK_CFDD826FBF396750 FOREIGN KEY (id) REFERENCES product (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart_items (id INT AUTO_INCREMENT NOT NULL, quantity INT NOT NULL, unit_price NUMERIC(8, 2) NOT NULL, created_at DATETIME NOT NULL, cart_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_BEF484454584665A (product_id), INDEX IDX_BEF484451AD5CDBF (cart_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE carts (id INT AUTO_INCREMENT NOT NULL, session_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, customer_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_4E004AAC9395C3F3 (customer_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE customers (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, roles JSON NOT NULL, password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, first_name VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, last_name VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, phone VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, address VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, postal_code VARCHAR(10) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, city VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE ingredients (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, price NUMERIC(8, 2) DEFAULT NULL, is_available TINYINT NOT NULL, image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE order_items (id INT AUTO_INCREMENT NOT NULL, product_name VARCHAR(150) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, quantity INT NOT NULL, unit_price NUMERIC(8, 2) NOT NULL, created_at DATETIME NOT NULL, order_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_62809DB08D9F6D38 (order_id), INDEX IDX_62809DB04584665A (product_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, reference VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, status VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, payment_status VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, total_amount NUMERIC(10, 2) NOT NULL, delivery_fee NUMERIC(8, 2) DEFAULT NULL, delivery_address VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, delivery_postal_code VARCHAR(10) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, delivery_city VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, customer_name VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, customer_email VARCHAR(180) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, customer_phone VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, notes LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, delivered_at DATETIME DEFAULT NULL, customer_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_E52FFDEEAEA34913 (reference), INDEX IDX_E52FFDEE9395C3F3 (customer_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE product_ingredients (product_id INT NOT NULL, ingredient_id INT NOT NULL, INDEX IDX_E74F8F504584665A (product_id), INDEX IDX_E74F8F50933FE08C (ingredient_id), PRIMARY KEY (product_id, ingredient_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE products (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(150) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, price NUMERIC(8, 2) NOT NULL, image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, is_available TINYINT NOT NULL, is_popular TINYINT NOT NULL, category VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE cart_items ADD CONSTRAINT `FK_BEF484451AD5CDBF` FOREIGN KEY (cart_id) REFERENCES carts (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE cart_items ADD CONSTRAINT `FK_BEF484454584665A` FOREIGN KEY (product_id) REFERENCES products (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE carts ADD CONSTRAINT `FK_4E004AAC9395C3F3` FOREIGN KEY (customer_id) REFERENCES customers (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE order_items ADD CONSTRAINT `FK_62809DB04584665A` FOREIGN KEY (product_id) REFERENCES products (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE order_items ADD CONSTRAINT `FK_62809DB08D9F6D38` FOREIGN KEY (order_id) REFERENCES orders (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT `FK_E52FFDEE9395C3F3` FOREIGN KEY (customer_id) REFERENCES customers (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE product_ingredients ADD CONSTRAINT `FK_E74F8F504584665A` FOREIGN KEY (product_id) REFERENCES products (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_ingredients ADD CONSTRAINT `FK_E74F8F50933FE08C` FOREIGN KEY (ingredient_id) REFERENCES ingredients (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dessert DROP FOREIGN KEY FK_79291B96BF396750');
        $this->addSql('ALTER TABLE drink DROP FOREIGN KEY FK_DBE40D1BF396750');
        $this->addSql('ALTER TABLE pasta DROP FOREIGN KEY FK_9B3BBC81BF396750');
        $this->addSql('DROP TABLE app_config');
        $this->addSql('DROP TABLE dessert');
        $this->addSql('DROP TABLE drink');
        $this->addSql('DROP TABLE pasta');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('ALTER TABLE pizza DROP FOREIGN KEY FK_CFDD826FBF396750');
        $this->addSql('ALTER TABLE pizza ADD name VARCHAR(100) NOT NULL, ADD image VARCHAR(255) NOT NULL, ADD price NUMERIC(5, 2) NOT NULL, ADD type VARCHAR(50) DEFAULT NULL, ADD popular TINYINT DEFAULT 0 NOT NULL, ADD active TINYINT DEFAULT 1 NOT NULL, ADD created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE id id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX id ON pizza (id)');
    }
}
