<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251101230254 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customers (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(11) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_products (id INT AUTO_INCREMENT NOT NULL, order_id INT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, subtotal NUMERIC(10, 2) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_5242B8EB8D9F6D38 (order_id), INDEX IDX_5242B8EB4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, total NUMERIC(10, 2) NOT NULL, status VARCHAR(32) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_E52FFDEE9395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(128) NOT NULL, price NUMERIC(10, 2) NOT NULL, category VARCHAR(32) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_products ADD CONSTRAINT FK_5242B8EB8D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE order_products ADD CONSTRAINT FK_5242B8EB4584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE9395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_products DROP FOREIGN KEY FK_5242B8EB8D9F6D38');
        $this->addSql('ALTER TABLE order_products DROP FOREIGN KEY FK_5242B8EB4584665A');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE9395C3F3');
        $this->addSql('DROP TABLE customers');
        $this->addSql('DROP TABLE order_products');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE users');
    }
}
