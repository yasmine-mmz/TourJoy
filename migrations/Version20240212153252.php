<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240212153252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE claims ADD fk_c_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE claims ADD CONSTRAINT FK_BEA313BE1DE945ED FOREIGN KEY (fk_c_id) REFERENCES categories (id)');
        $this->addSql('CREATE INDEX IDX_BEA313BE1DE945ED ON claims (fk_c_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE claims DROP FOREIGN KEY FK_BEA313BE1DE945ED');
        $this->addSql('DROP INDEX IDX_BEA313BE1DE945ED ON claims');
        $this->addSql('ALTER TABLE claims DROP fk_c_id');
    }
}
