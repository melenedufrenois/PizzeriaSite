<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260302095007 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE dessert CHANGE contains_allergens contains_allergens TINYINT NOT NULL');
        $this->addSql('ALTER TABLE drink CHANGE is_alcoholic is_alcoholic TINYINT NOT NULL');
        $this->addSql('ALTER TABLE product ADD allergens JSON NOT NULL, CHANGE popular popular TINYINT NOT NULL, CHANGE active active TINYINT NOT NULL, CHANGE created_at created_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE dessert CHANGE contains_allergens contains_allergens TINYINT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE drink CHANGE is_alcoholic is_alcoholic TINYINT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE product DROP allergens, CHANGE popular popular TINYINT DEFAULT 0 NOT NULL, CHANGE active active TINYINT DEFAULT 1 NOT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }
}
