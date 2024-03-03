<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240303095037 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favs (user INT DEFAULT NULL, acc INT DEFAULT NULL, idF INT AUTO_INCREMENT NOT NULL, INDEX IDX_C67528228D93D649 (user), INDEX IDX_C67528222C3F7083 (acc), PRIMARY KEY(idF)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favs ADD CONSTRAINT FK_C67528228D93D649 FOREIGN KEY (user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favs ADD CONSTRAINT FK_C67528222C3F7083 FOREIGN KEY (acc) REFERENCES accomodation (refA)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favs DROP FOREIGN KEY FK_C67528228D93D649');
        $this->addSql('ALTER TABLE favs DROP FOREIGN KEY FK_C67528222C3F7083');
        $this->addSql('DROP TABLE favs');
    }
}
