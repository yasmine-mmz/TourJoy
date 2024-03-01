<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240228142752 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, message VARCHAR(255) DEFAULT NULL, is_read TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categories CHANGE name name VARCHAR(16) NOT NULL');
        $this->addSql('ALTER TABLE claims DROP FOREIGN KEY FK_BEA313BE1DE945ED');
        $this->addSql('ALTER TABLE claims CHANGE title title VARCHAR(24) NOT NULL, CHANGE description description VARCHAR(255) NOT NULL, CHANGE state state VARCHAR(255) NOT NULL, CHANGE reply reply VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE claims ADD CONSTRAINT FK_BEA313BE1DE945ED FOREIGN KEY (fk_c_id) REFERENCES categories (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE notification');
        $this->addSql('ALTER TABLE categories CHANGE name name VARCHAR(16) DEFAULT NULL');
        $this->addSql('ALTER TABLE claims DROP FOREIGN KEY FK_BEA313BE1DE945ED');
        $this->addSql('ALTER TABLE claims CHANGE title title VARCHAR(24) DEFAULT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL, CHANGE state state VARCHAR(255) DEFAULT NULL, CHANGE reply reply VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE claims ADD CONSTRAINT FK_BEA313BE1DE945ED FOREIGN KEY (fk_c_id) REFERENCES categories (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
