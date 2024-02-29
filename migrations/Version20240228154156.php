<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240228154156 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE accomodation (fkpays_id INT DEFAULT NULL, refA INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, image_name VARCHAR(255) NOT NULL, nb_rooms INT NOT NULL, price DOUBLE PRECISION NOT NULL, location VARCHAR(255) NOT NULL, INDEX IDX_520D81B37F79CD57 (fkpays_id), PRIMARY KEY(refA)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, region VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_5373C9665E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE monument (ref INT AUTO_INCREMENT NOT NULL, fkcountry_id INT DEFAULT NULL, name_m VARCHAR(255) NOT NULL, image_name VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, entryprice INT NOT NULL, status VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, INDEX IDX_7BB88283D5EA48FD (fkcountry_id), PRIMARY KEY(ref)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (name INT DEFAULT NULL, idR INT AUTO_INCREMENT NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, INDEX IDX_42C849555E237E06 (name), PRIMARY KEY(idR)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE accomodation ADD CONSTRAINT FK_520D81B37F79CD57 FOREIGN KEY (fkpays_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE monument ADD CONSTRAINT FK_7BB88283D5EA48FD FOREIGN KEY (fkcountry_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849555E237E06 FOREIGN KEY (name) REFERENCES accomodation (refA)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE accomodation DROP FOREIGN KEY FK_520D81B37F79CD57');
        $this->addSql('ALTER TABLE monument DROP FOREIGN KEY FK_7BB88283D5EA48FD');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849555E237E06');
        $this->addSql('DROP TABLE accomodation');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE monument');
        $this->addSql('DROP TABLE reservation');
    }
}
