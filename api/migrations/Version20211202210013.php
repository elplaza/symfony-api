<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211202210013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book ADD isbn VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE book ADD title VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE book ADD description TEXT NOT NULL');
        $this->addSql('ALTER TABLE book ADD author VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE book ADD publication_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('COMMENT ON COLUMN book.publication_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE review ADD book_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE review ADD rating SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE review ADD body TEXT NOT NULL');
        $this->addSql('ALTER TABLE review ADD author VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE review ADD publication_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('COMMENT ON COLUMN review.publication_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C616A2B381 FOREIGN KEY (book_id) REFERENCES book (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_794381C616A2B381 ON review (book_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE book DROP isbn');
        $this->addSql('ALTER TABLE book DROP title');
        $this->addSql('ALTER TABLE book DROP description');
        $this->addSql('ALTER TABLE book DROP author');
        $this->addSql('ALTER TABLE book DROP publication_date');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C616A2B381');
        $this->addSql('DROP INDEX IDX_794381C616A2B381');
        $this->addSql('ALTER TABLE review DROP book_id');
        $this->addSql('ALTER TABLE review DROP rating');
        $this->addSql('ALTER TABLE review DROP body');
        $this->addSql('ALTER TABLE review DROP author');
        $this->addSql('ALTER TABLE review DROP publication_date');
    }
}
