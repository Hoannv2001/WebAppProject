<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221024124546 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_book (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE book ADD book_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A33116A2B381 FOREIGN KEY (book_id) REFERENCES order_book (id)');
        $this->addSql('CREATE INDEX IDX_CBE5A33116A2B381 ON book (book_id)');
        $this->addSql('ALTER TABLE `order` ADD order_b_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398649AC6A1 FOREIGN KEY (order_b_id) REFERENCES order_book (id)');
        $this->addSql('CREATE INDEX IDX_F5299398649AC6A1 ON `order` (order_b_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A33116A2B381');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398649AC6A1');
        $this->addSql('DROP TABLE order_book');
        $this->addSql('DROP INDEX IDX_CBE5A33116A2B381 ON book');
        $this->addSql('ALTER TABLE book DROP book_id');
        $this->addSql('DROP INDEX IDX_F5299398649AC6A1 ON `order`');
        $this->addSql('ALTER TABLE `order` DROP order_b_id');
    }
}
