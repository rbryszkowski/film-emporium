<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210910074623 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_8244BE222B36786B');
        $this->addSql('DROP INDEX UNIQ_8244BE226768CA8A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__film AS SELECT id, feature_film_id, title, description, genres, director FROM film');
        $this->addSql('DROP TABLE film');
        $this->addSql('CREATE TABLE film (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, feature_film_id INTEGER DEFAULT NULL, title VARCHAR(50) NOT NULL COLLATE BINARY, description VARCHAR(255) DEFAULT NULL COLLATE BINARY, genres VARCHAR(255) DEFAULT NULL COLLATE BINARY, director VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_8244BE226768CA8A FOREIGN KEY (feature_film_id) REFERENCES feature_film (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO film (id, feature_film_id, title, description, genres, director) SELECT id, feature_film_id, title, description, genres, director FROM __temp__film');
        $this->addSql('DROP TABLE __temp__film');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8244BE222B36786B ON film (title)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8244BE226768CA8A ON film (feature_film_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_8244BE222B36786B');
        $this->addSql('DROP INDEX UNIQ_8244BE226768CA8A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__film AS SELECT id, feature_film_id, title, genres, director, description FROM film');
        $this->addSql('DROP TABLE film');
        $this->addSql('CREATE TABLE film (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, feature_film_id INTEGER DEFAULT NULL, title VARCHAR(50) NOT NULL, genres VARCHAR(255) DEFAULT NULL, director VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO film (id, feature_film_id, title, genres, director, description) SELECT id, feature_film_id, title, genres, director, description FROM __temp__film');
        $this->addSql('DROP TABLE __temp__film');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8244BE222B36786B ON film (title)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8244BE226768CA8A ON film (feature_film_id)');
    }
}
