<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260202200000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Refactor to JOINED inheritance: Product as parent, Pizza/Pasta/Dessert/Drink as children';
    }

    public function up(Schema $schema): void
    {
        // Drop old product table if exists
        $this->addSql('DROP TABLE IF EXISTS product CASCADE');
        
        // Create parent table: product
        $this->addSql('CREATE TABLE product (
            id SERIAL PRIMARY KEY,
            dtype VARCHAR(255) NOT NULL,
            name VARCHAR(100) NOT NULL,
            description TEXT DEFAULT NULL,
            image VARCHAR(255) NOT NULL,
            price DECIMAL(5,2) NOT NULL,
            type VARCHAR(50) DEFAULT NULL,
            popular BOOLEAN NOT NULL DEFAULT FALSE,
            active BOOLEAN NOT NULL DEFAULT TRUE,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        )');

        // Create child table: pizza
        $this->addSql('CREATE TABLE pizza (
            id INT NOT NULL PRIMARY KEY REFERENCES product(id) ON DELETE CASCADE,
            base VARCHAR(20) NOT NULL,
            ingredients JSON NOT NULL
        )');

        // Create child table: pasta
        $this->addSql('CREATE TABLE pasta (
            id INT NOT NULL PRIMARY KEY REFERENCES product(id) ON DELETE CASCADE,
            ingredients JSON NOT NULL,
            pasta_type VARCHAR(50) DEFAULT NULL
        )');

        // Create child table: dessert
        $this->addSql('CREATE TABLE dessert (
            id INT NOT NULL PRIMARY KEY REFERENCES product(id) ON DELETE CASCADE,
            ingredients JSON NOT NULL,
            contains_allergens BOOLEAN NOT NULL DEFAULT FALSE
        )');

        // Create child table: drink
        $this->addSql('CREATE TABLE drink (
            id INT NOT NULL PRIMARY KEY REFERENCES product(id) ON DELETE CASCADE,
            volume VARCHAR(20) DEFAULT NULL,
            is_alcoholic BOOLEAN NOT NULL DEFAULT FALSE
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS drink');
        $this->addSql('DROP TABLE IF EXISTS dessert');
        $this->addSql('DROP TABLE IF EXISTS pasta');
        $this->addSql('DROP TABLE IF EXISTS pizza');
        $this->addSql('DROP TABLE IF EXISTS product');
    }
}
