<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240302004635 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE claims ADD fk_u_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE claims ADD CONSTRAINT FK_BEA313BE689B4DAE FOREIGN KEY (fk_u_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_BEA313BE689B4DAE ON claims (fk_u_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE claims DROP FOREIGN KEY FK_BEA313BE689B4DAE');
        $this->addSql('DROP INDEX IDX_BEA313BE689B4DAE ON claims');
        $this->addSql('ALTER TABLE claims DROP fk_u_id');
    }
}
