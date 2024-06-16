<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240615144027 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favourite_track (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, track_info JSON NOT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_779679D3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recognition_history (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, track_info JSON NOT NULL COMMENT \'(DC2Type:json)\', recognition_date DATETIME NOT NULL, INDEX IDX_DBC0386CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favourite_track ADD CONSTRAINT FK_779679D3A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE recognition_history ADD CONSTRAINT FK_DBC0386CA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favourite_track DROP FOREIGN KEY FK_779679D3A76ED395');
        $this->addSql('ALTER TABLE recognition_history DROP FOREIGN KEY FK_DBC0386CA76ED395');
        $this->addSql('DROP TABLE favourite_track');
        $this->addSql('DROP TABLE recognition_history');
        $this->addSql('DROP TABLE `user`');
    }
}
