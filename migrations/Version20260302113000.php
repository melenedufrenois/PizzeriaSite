<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260302113000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create app_config table for runtime configuration (contact recipient email).';
    }

    public function up(Schema $schema): void
    {
        if ($schema->hasTable('app_config')) {
            return;
        }

        $table = $schema->createTable('app_config');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('config_key', 'string', ['length' => 100]);
        $table->addColumn('config_value', 'string', ['length' => 255]);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['config_key'], 'uniq_app_config_key');
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('app_config')) {
            $schema->dropTable('app_config');
        }
    }
}
