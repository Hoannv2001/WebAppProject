<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221023150956 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book ADD email VARCHAR(180) DEFAULT NULL');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331E7927C74 FOREIGN KEY (email) REFERENCES user (email)');
        $this->addSql('CREATE INDEX IDX_CBE5A331E7927C74 ON book (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A331E7927C74');
        $this->addSql('DROP INDEX IDX_CBE5A331E7927C74 ON book');
        $this->addSql('ALTER TABLE book DROP email');
    }
}
