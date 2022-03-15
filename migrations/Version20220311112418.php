<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220311112418 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `groups` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images (id INT AUTO_INCREMENT NOT NULL, trick_id_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_E01FBE6AB46B9EE8 (trick_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tricks (id INT AUTO_INCREMENT NOT NULL, group_id_id INT NOT NULL, user_id_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, videos LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_E1D902C12F68B530 (group_id_id), INDEX IDX_E1D902C19D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6AB46B9EE8 FOREIGN KEY (trick_id_id) REFERENCES tricks (id)');
        $this->addSql('ALTER TABLE tricks ADD CONSTRAINT FK_E1D902C12F68B530 FOREIGN KEY (group_id_id) REFERENCES `groups` (id)');
        $this->addSql('ALTER TABLE tricks ADD CONSTRAINT FK_E1D902C19D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tricks DROP FOREIGN KEY FK_E1D902C12F68B530');
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6AB46B9EE8');
        $this->addSql('DROP TABLE `groups`');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE tricks');
    }
}
