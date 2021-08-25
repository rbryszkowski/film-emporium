<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210825100231 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE director (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__film AS SELECT id, title, description FROM film');
        $this->addSql('DROP TABLE film');
        $this->addSql('CREATE TABLE film (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, director_id INTEGER DEFAULT NULL, title VARCHAR(50) NOT NULL COLLATE BINARY, description VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_8244BE22899FB366 FOREIGN KEY (director_id) REFERENCES director (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO film (id, title, description) SELECT id, title, description FROM __temp__film');
        $this->addSql('DROP TABLE __temp__film');
        $this->addSql('CREATE INDEX IDX_8244BE22899FB366 ON film (director_id)');
        $this->addSql('DROP INDEX IDX_1A3CCDA84296D31F');
        $this->addSql('DROP INDEX IDX_1A3CCDA8567F5183');
        $this->addSql('CREATE TEMPORARY TABLE __temp__film_genre AS SELECT film_id, genre_id FROM film_genre');
        $this->addSql('DROP TABLE film_genre');
        $this->addSql('CREATE TABLE film_genre (film_id INTEGER NOT NULL, genre_id INTEGER NOT NULL, PRIMARY KEY(film_id, genre_id), CONSTRAINT FK_1A3CCDA8567F5183 FOREIGN KEY (film_id) REFERENCES film (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_1A3CCDA84296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO film_genre (film_id, genre_id) SELECT film_id, genre_id FROM __temp__film_genre');
        $this->addSql('DROP TABLE __temp__film_genre');
        $this->addSql('CREATE INDEX IDX_1A3CCDA84296D31F ON film_genre (genre_id)');
        $this->addSql('CREATE INDEX IDX_1A3CCDA8567F5183 ON film_genre (film_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE director');
        $this->addSql('DROP INDEX IDX_8244BE22899FB366');
        $this->addSql('CREATE TEMPORARY TABLE __temp__film AS SELECT id, title, description FROM film');
        $this->addSql('DROP TABLE film');
        $this->addSql('CREATE TABLE film (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO film (id, title, description) SELECT id, title, description FROM __temp__film');
        $this->addSql('DROP TABLE __temp__film');
        $this->addSql('DROP INDEX IDX_1A3CCDA8567F5183');
        $this->addSql('DROP INDEX IDX_1A3CCDA84296D31F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__film_genre AS SELECT film_id, genre_id FROM film_genre');
        $this->addSql('DROP TABLE film_genre');
        $this->addSql('CREATE TABLE film_genre (film_id INTEGER NOT NULL, genre_id INTEGER NOT NULL, PRIMARY KEY(film_id, genre_id))');
        $this->addSql('INSERT INTO film_genre (film_id, genre_id) SELECT film_id, genre_id FROM __temp__film_genre');
        $this->addSql('DROP TABLE __temp__film_genre');
        $this->addSql('CREATE INDEX IDX_1A3CCDA8567F5183 ON film_genre (film_id)');
        $this->addSql('CREATE INDEX IDX_1A3CCDA84296D31F ON film_genre (genre_id)');
    }
}
