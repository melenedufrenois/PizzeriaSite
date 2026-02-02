<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260202000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create pizza table with base field for categorization';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE pizza (
            id SERIAL PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            image VARCHAR(255) NOT NULL,
            price DECIMAL(5,2) NOT NULL,
            ingredients JSON NOT NULL,
            base VARCHAR(20) NOT NULL,
            type VARCHAR(50) DEFAULT NULL,
            popular BOOLEAN NOT NULL DEFAULT FALSE,
            active BOOLEAN NOT NULL DEFAULT TRUE,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE pizza');
    }
}
