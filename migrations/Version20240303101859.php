<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240303101859 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE guide ADD country_id INT DEFAULT NULL, DROP country_g');
        $this->addSql('ALTER TABLE guide ADD CONSTRAINT FK_CA9EC735F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('CREATE INDEX IDX_CA9EC735F92F3E70 ON guide (country_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE guide DROP FOREIGN KEY FK_CA9EC735F92F3E70');
        $this->addSql('DROP INDEX IDX_CA9EC735F92F3E70 ON guide');
        $this->addSql('ALTER TABLE guide ADD country_g VARCHAR(255) NOT NULL, DROP country_id');
    }
}
