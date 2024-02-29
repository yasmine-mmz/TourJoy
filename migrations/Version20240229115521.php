<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240229115521 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE accomodation (fkpays_id INT DEFAULT NULL, refA INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, image_name VARCHAR(255) NOT NULL, nb_rooms INT NOT NULL, price DOUBLE PRECISION NOT NULL, location VARCHAR(255) NOT NULL, INDEX IDX_520D81B37F79CD57 (fkpays_id), PRIMARY KEY(refA)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, guide_id INT DEFAULT NULL, user_id INT DEFAULT NULL, date DATE NOT NULL, UNIQUE INDEX UNIQ_E00CEDDEAA9E377A (date), INDEX IDX_E00CEDDED7ED1D4B (guide_id), INDEX IDX_E00CEDDEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feedback (id INT AUTO_INCREMENT NOT NULL, fk_guide_id INT DEFAULT NULL, user_id_id INT NOT NULL, comment VARCHAR(500) NOT NULL, rating INT NOT NULL, useful INT DEFAULT NULL, not_useful INT DEFAULT NULL, INDEX IDX_D2294458E5C55E95 (fk_guide_id), INDEX IDX_D22944589D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE guide (CIN INT NOT NULL, image_name VARCHAR(255) DEFAULT NULL, firstname_g VARCHAR(255) NOT NULL, lastname_g VARCHAR(255) NOT NULL, emailaddress_g VARCHAR(255) NOT NULL, phonenumber_g VARCHAR(255) NOT NULL, country_g VARCHAR(255) NOT NULL, gender_g VARCHAR(255) NOT NULL, language VARCHAR(255) NOT NULL, dob DATE NOT NULL, PRIMARY KEY(CIN)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE likesystem (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, feedback_id INT DEFAULT NULL, useful INT DEFAULT NULL, not_useful INT DEFAULT NULL, INDEX IDX_F411D4F0A76ED395 (user_id), INDEX IDX_F411D4F0D249A887 (feedback_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (name INT DEFAULT NULL, fkuser_id INT DEFAULT NULL, idR INT AUTO_INCREMENT NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, INDEX IDX_42C849555E237E06 (name), INDEX IDX_42C849557EF35C86 (fkuser_id), PRIMARY KEY(idR)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) DEFAULT NULL, first_name VARCHAR(25) DEFAULT NULL, last_name VARCHAR(25) DEFAULT NULL, phone_number INT DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, profile_picture VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', google_authenticator_secret VARCHAR(255) DEFAULT NULL, modified_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_verified TINYINT(1) NOT NULL, is_banned TINYINT(1) NOT NULL, google_id VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE accomodation ADD CONSTRAINT FK_520D81B37F79CD57 FOREIGN KEY (fkpays_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDED7ED1D4B FOREIGN KEY (guide_id) REFERENCES guide (CIN)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D2294458E5C55E95 FOREIGN KEY (fk_guide_id) REFERENCES guide (CIN)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D22944589D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE likesystem ADD CONSTRAINT FK_F411D4F0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE likesystem ADD CONSTRAINT FK_F411D4F0D249A887 FOREIGN KEY (feedback_id) REFERENCES feedback (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849555E237E06 FOREIGN KEY (name) REFERENCES accomodation (refA)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849557EF35C86 FOREIGN KEY (fkuser_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5373C9665E237E06 ON country (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE accomodation DROP FOREIGN KEY FK_520D81B37F79CD57');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDED7ED1D4B');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEA76ED395');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D2294458E5C55E95');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D22944589D86650F');
        $this->addSql('ALTER TABLE likesystem DROP FOREIGN KEY FK_F411D4F0A76ED395');
        $this->addSql('ALTER TABLE likesystem DROP FOREIGN KEY FK_F411D4F0D249A887');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849555E237E06');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849557EF35C86');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE accomodation');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE feedback');
        $this->addSql('DROP TABLE guide');
        $this->addSql('DROP TABLE likesystem');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX UNIQ_5373C9665E237E06 ON country');
    }
}
