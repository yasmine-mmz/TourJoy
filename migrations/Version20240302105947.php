<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240302105947 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE accomodation (fkpays_id INT DEFAULT NULL, refA INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, image_name VARCHAR(255) NOT NULL, nb_rooms INT NOT NULL, price DOUBLE PRECISION NOT NULL, location VARCHAR(255) NOT NULL, INDEX IDX_520D81B37F79CD57 (fkpays_id), PRIMARY KEY(refA)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(16) NOT NULL, UNIQUE INDEX UNIQ_3AF346685E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE claims (id INT AUTO_INCREMENT NOT NULL, fk_c_id INT DEFAULT NULL, fk_u_id INT DEFAULT NULL, title VARCHAR(24) NOT NULL, description VARCHAR(255) NOT NULL, create_date DATE NOT NULL, state VARCHAR(255) NOT NULL, reply VARCHAR(255) NOT NULL, INDEX IDX_BEA313BE1DE945ED (fk_c_id), INDEX IDX_BEA313BE689B4DAE (fk_u_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, region VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_5373C9665E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE monument (ref INT AUTO_INCREMENT NOT NULL, fkcountry_id INT DEFAULT NULL, name_m VARCHAR(255) NOT NULL, image_name VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, entryprice INT NOT NULL, status VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, INDEX IDX_7BB88283D5EA48FD (fkcountry_id), PRIMARY KEY(ref)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, message VARCHAR(255) DEFAULT NULL, is_read TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', user VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (name INT DEFAULT NULL, fkuser_id INT DEFAULT NULL, idR INT AUTO_INCREMENT NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, INDEX IDX_42C849555E237E06 (name), INDEX IDX_42C849557EF35C86 (fkuser_id), PRIMARY KEY(idR)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscription (id INT AUTO_INCREMENT NOT NULL, type_t_id INT DEFAULT NULL, user_id_id INT DEFAULT NULL, plan VARCHAR(255) NOT NULL, duration INT NOT NULL, UNIQUE INDEX UNIQ_A3C664D3DD5A5B7D (plan), INDEX IDX_A3C664D38B1631FD (type_t_id), INDEX IDX_A3C664D39D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transport (id INT AUTO_INCREMENT NOT NULL, type_t VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_66AB212E9ADB3123 (type_t), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE accomodation ADD CONSTRAINT FK_520D81B37F79CD57 FOREIGN KEY (fkpays_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE claims ADD CONSTRAINT FK_BEA313BE1DE945ED FOREIGN KEY (fk_c_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE claims ADD CONSTRAINT FK_BEA313BE689B4DAE FOREIGN KEY (fk_u_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE monument ADD CONSTRAINT FK_7BB88283D5EA48FD FOREIGN KEY (fkcountry_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849555E237E06 FOREIGN KEY (name) REFERENCES accomodation (refA)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849557EF35C86 FOREIGN KEY (fkuser_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D38B1631FD FOREIGN KEY (type_t_id) REFERENCES transport (id)');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D39D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE accomodation DROP FOREIGN KEY FK_520D81B37F79CD57');
        $this->addSql('ALTER TABLE claims DROP FOREIGN KEY FK_BEA313BE1DE945ED');
        $this->addSql('ALTER TABLE claims DROP FOREIGN KEY FK_BEA313BE689B4DAE');
        $this->addSql('ALTER TABLE monument DROP FOREIGN KEY FK_7BB88283D5EA48FD');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849555E237E06');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849557EF35C86');
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D38B1631FD');
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D39D86650F');
        $this->addSql('DROP TABLE accomodation');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE claims');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE monument');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE subscription');
        $this->addSql('DROP TABLE transport');
    }
}
