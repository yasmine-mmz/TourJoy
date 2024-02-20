<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240217214100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, guide_id INT DEFAULT NULL, date DATE NOT NULL, INDEX IDX_E00CEDDED7ED1D4B (guide_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feedback (id INT AUTO_INCREMENT NOT NULL, fk_guide_id INT DEFAULT NULL, comment VARCHAR(500) NOT NULL, rating INT NOT NULL, INDEX IDX_D2294458E5C55E95 (fk_guide_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE guide (CIN INT NOT NULL, image_name VARCHAR(255) DEFAULT NULL, firstname_g VARCHAR(255) NOT NULL, lastname_g VARCHAR(255) NOT NULL, emailaddress_g VARCHAR(255) NOT NULL, phonenumber_g VARCHAR(255) NOT NULL, country_g VARCHAR(255) NOT NULL, gender_g VARCHAR(255) NOT NULL, PRIMARY KEY(CIN)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDED7ED1D4B FOREIGN KEY (guide_id) REFERENCES guide (CIN)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D2294458E5C55E95 FOREIGN KEY (fk_guide_id) REFERENCES guide (CIN)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDED7ED1D4B');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D2294458E5C55E95');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE feedback');
        $this->addSql('DROP TABLE guide');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
