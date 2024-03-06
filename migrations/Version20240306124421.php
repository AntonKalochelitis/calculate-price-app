<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306124421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, alpha2 VARCHAR(2) NOT NULL, alpha3 VARCHAR(3) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coupon (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, currency_id INT NOT NULL, coupon VARCHAR(255) NOT NULL, value INT NOT NULL, status INT NOT NULL, INDEX IDX_64BF3F02C54C8C93 (type_id), INDEX IDX_64BF3F0238248176 (currency_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE currency (id INT AUTO_INCREMENT NOT NULL, symbol VARCHAR(3) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_processor (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, status INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, currency_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, price INT NOT NULL, INDEX IDX_D34A04AD38248176 (currency_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purchase_order (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, currency_id INT NOT NULL, coupon_id INT DEFAULT NULL, price INT DEFAULT NULL, tax INT NOT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_21E210B24584665A (product_id), INDEX IDX_21E210B238248176 (currency_id), UNIQUE INDEX UNIQ_21E210B266C5951B (coupon_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tax (id INT AUTO_INCREMENT NOT NULL, country_id INT NOT NULL, code VARCHAR(2) NOT NULL, value INT NOT NULL, INDEX IDX_8E81BA76F92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_coupon (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE coupon ADD CONSTRAINT FK_64BF3F02C54C8C93 FOREIGN KEY (type_id) REFERENCES type_coupon (id)');
        $this->addSql('ALTER TABLE coupon ADD CONSTRAINT FK_64BF3F0238248176 FOREIGN KEY (currency_id) REFERENCES currency (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD38248176 FOREIGN KEY (currency_id) REFERENCES currency (id)');
        $this->addSql('ALTER TABLE purchase_order ADD CONSTRAINT FK_21E210B24584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE purchase_order ADD CONSTRAINT FK_21E210B238248176 FOREIGN KEY (currency_id) REFERENCES currency (id)');
        $this->addSql('ALTER TABLE purchase_order ADD CONSTRAINT FK_21E210B266C5951B FOREIGN KEY (coupon_id) REFERENCES coupon (id)');
        $this->addSql('ALTER TABLE tax ADD CONSTRAINT FK_8E81BA76F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coupon DROP FOREIGN KEY FK_64BF3F02C54C8C93');
        $this->addSql('ALTER TABLE coupon DROP FOREIGN KEY FK_64BF3F0238248176');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD38248176');
        $this->addSql('ALTER TABLE purchase_order DROP FOREIGN KEY FK_21E210B24584665A');
        $this->addSql('ALTER TABLE purchase_order DROP FOREIGN KEY FK_21E210B238248176');
        $this->addSql('ALTER TABLE purchase_order DROP FOREIGN KEY FK_21E210B266C5951B');
        $this->addSql('ALTER TABLE tax DROP FOREIGN KEY FK_8E81BA76F92F3E70');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE coupon');
        $this->addSql('DROP TABLE currency');
        $this->addSql('DROP TABLE payment_processor');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE purchase_order');
        $this->addSql('DROP TABLE tax');
        $this->addSql('DROP TABLE type_coupon');
    }
}
