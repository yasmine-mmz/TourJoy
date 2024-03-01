<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240301175607 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE subscription (id INT AUTO_INCREMENT NOT NULL, type_t_id INT DEFAULT NULL, plan VARCHAR(255) NOT NULL, duration INT NOT NULL, UNIQUE INDEX UNIQ_A3C664D3DD5A5B7D (plan), INDEX IDX_A3C664D38B1631FD (type_t_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transport (id INT AUTO_INCREMENT NOT NULL, type_t VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_66AB212E9ADB3123 (type_t), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D38B1631FD FOREIGN KEY (type_t_id) REFERENCES transport (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D38B1631FD');
        $this->addSql('DROP TABLE subscription');
        $this->addSql('DROP TABLE transport');
    }
}
