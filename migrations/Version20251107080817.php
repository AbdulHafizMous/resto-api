<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251107080817 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE orders (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, table_number INTEGER NOT NULL, items CLOB NOT NULL --(DC2Type:json)
        , total_amount NUMERIC(10, 2) NOT NULL, status VARCHAR(20) NOT NULL, customer_note CLOB DEFAULT NULL, created_at DATETIME NOT NULL)');
        $this->addSql('CREATE TABLE reservations (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, customer_name VARCHAR(100) NOT NULL, customer_email VARCHAR(180) NOT NULL, customer_phone VARCHAR(15) NOT NULL, date DATE NOT NULL, time TIME NOT NULL, number_of_guests INTEGER NOT NULL, special_requests CLOB DEFAULT NULL, status VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE reservations');
    }
}
