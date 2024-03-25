<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240325165555 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO `country` (`id`, `name`, `alpha2`, `alpha3`) VALUES (1, 'Germany', 'DE', 'DEU');");
        $this->addSql("INSERT INTO `country` (`id`, `name`, `alpha2`, `alpha3`) VALUES (2, 'Italy', 'IT', 'ITA');");
        $this->addSql("INSERT INTO `country` (`id`, `name`, `alpha2`, `alpha3`) VALUES (3, 'France', 'FR', 'FRA');");
        $this->addSql("INSERT INTO `country` (`id`, `name`, `alpha2`, `alpha3`) VALUES (4, 'Greece', 'GR', 'GRC');");

        $this->addSql("INSERT INTO `currency` (`id`, `symbol`, `name`) VALUES (1, 'USD', 'Dollar USA');");
        $this->addSql("INSERT INTO `currency` (`id`, `symbol`, `name`) VALUES (2, 'EUR', 'Euro');");

        $this->addSql("INSERT INTO `product` (`id`, `currency_id`, `name`, `price`) VALUES (1, 2, 'Iphone', 10000);");
        $this->addSql("INSERT INTO `product` (`id`, `currency_id`, `name`, `price`) VALUES (2, 2, 'Наушники', 2000);");
        $this->addSql("INSERT INTO `product` (`id`, `currency_id`, `name`, `price`) VALUES (3, 2, 'Чехол', 1000);");

        $this->addSql("INSERT INTO `type_coupon` (`id`, `name`) VALUES (1, 'fixed');");
        $this->addSql("INSERT INTO `type_coupon` (`id`, `name`) VALUES (2, 'percent');");

        $this->addSql("INSERT INTO `tax` (`country_id`, `code`, `value`) VALUES (1, 'DE', 19);");
        $this->addSql("INSERT INTO `tax` (`country_id`, `code`, `value`) VALUES (2, 'IT', 22);");
        $this->addSql("INSERT INTO `tax` (`country_id`, `code`, `value`) VALUES (3, 'FR', 20);");
        $this->addSql("INSERT INTO `tax` (`country_id`, `code`, `value`) VALUES (4, 'GR', 24);");

        $this->addSql("INSERT INTO `payment_processor` (`id`, `name`, `status`) VALUES (1, 'paypal', 1);");
        $this->addSql("INSERT INTO `payment_processor` (`id`, `name`, `status`) VALUES (2, 'stripe', 1);");
    }

    public function down(Schema $schema): void
    {

    }
}
