<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251104010834 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_payments (id INT AUTO_INCREMENT NOT NULL, payment_id INT NOT NULL, order_id INT NOT NULL, amount_applied NUMERIC(10, 2) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_842A79834C3A3BB (payment_id), INDEX IDX_842A79838D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payments (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, amount NUMERIC(10, 2) NOT NULL, date DATE NOT NULL, method VARCHAR(32) NOT NULL, notes VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_65D29B329395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_payments ADD CONSTRAINT FK_842A79834C3A3BB FOREIGN KEY (payment_id) REFERENCES payments (id)');
        $this->addSql('ALTER TABLE order_payments ADD CONSTRAINT FK_842A79838D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT FK_65D29B329395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_payments DROP FOREIGN KEY FK_842A79834C3A3BB');
        $this->addSql('ALTER TABLE order_payments DROP FOREIGN KEY FK_842A79838D9F6D38');
        $this->addSql('ALTER TABLE payments DROP FOREIGN KEY FK_65D29B329395C3F3');
        $this->addSql('DROP TABLE order_payments');
        $this->addSql('DROP TABLE payments');
    }
}
