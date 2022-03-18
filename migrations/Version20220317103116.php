<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220317103116 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE videos (id INT AUTO_INCREMENT NOT NULL, trick_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_29AA6432B281BE2E (trick_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE videos ADD CONSTRAINT FK_29AA6432B281BE2E FOREIGN KEY (trick_id) REFERENCES tricks (id)');
        $this->addSql('ALTER TABLE tricks DROP videos');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE videos');
        $this->addSql('ALTER TABLE tricks ADD videos LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
    }
}
