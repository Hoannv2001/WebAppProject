<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221024040823 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD customerEmail VARCHAR(180) DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993982FE7871C FOREIGN KEY (customerEmail) REFERENCES user (email)');
        $this->addSql('CREATE INDEX IDX_F52993982FE7871C ON `order` (customerEmail)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993982FE7871C');
        $this->addSql('DROP INDEX IDX_F52993982FE7871C ON `order`');
        $this->addSql('ALTER TABLE `order` DROP customerEmail');
    }
}
