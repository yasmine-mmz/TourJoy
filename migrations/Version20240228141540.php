<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240228141540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE accomodation ADD fkpays_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE accomodation ADD CONSTRAINT FK_520D81B37F79CD57 FOREIGN KEY (fkpays_id) REFERENCES country (id)');
        $this->addSql('CREATE INDEX IDX_520D81B37F79CD57 ON accomodation (fkpays_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE accomodation DROP FOREIGN KEY FK_520D81B37F79CD57');
        $this->addSql('DROP INDEX IDX_520D81B37F79CD57 ON accomodation');
        $this->addSql('ALTER TABLE accomodation DROP fkpays_id');
    }
}
