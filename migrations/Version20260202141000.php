<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260202141000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add base_type to products and backfill from ingredients';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("ALTER TABLE products ADD base_type VARCHAR(20) NOT NULL DEFAULT 'tomate'");
        $this->addSql("UPDATE products SET base_type = 'tomate' WHERE base_type IS NULL OR base_type = ''");
        $this->addSql(
            "UPDATE products p
            INNER JOIN product_ingredients pi ON pi.product_id = p.id
            INNER JOIN ingredients i ON i.id = pi.ingredient_id
            SET p.base_type = 'creme'
            WHERE i.name = 'Crème fraîche'"
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE products DROP base_type');
    }
}

