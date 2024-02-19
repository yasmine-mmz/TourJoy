<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240219092826 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categories CHANGE name name VARCHAR(16) DEFAULT NULL');
        $this->addSql('ALTER TABLE claims ADD fk_user_id INT DEFAULT NULL, CHANGE title title VARCHAR(24) DEFAULT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL, CHANGE state state VARCHAR(255) DEFAULT NULL, CHANGE reply reply VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE claims ADD CONSTRAINT FK_BEA313BE5741EEB9 FOREIGN KEY (fk_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_BEA313BE5741EEB9 ON claims (fk_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE claims DROP FOREIGN KEY FK_BEA313BE5741EEB9');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE categories CHANGE name name VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX IDX_BEA313BE5741EEB9 ON claims');
        $this->addSql('ALTER TABLE claims DROP fk_user_id, CHANGE title title VARCHAR(255) NOT NULL, CHANGE description description VARCHAR(255) NOT NULL, CHANGE state state VARCHAR(255) NOT NULL, CHANGE reply reply VARCHAR(255) NOT NULL');
    }
}
