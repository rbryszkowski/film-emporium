<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210825100420 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO director (id, name) VALUES (NULL, 'Stephen Spielberg')");
        $this->addSql("INSERT INTO director (id, name) VALUES (NULL, 'Quentin Tarantino')");
        $this->addSql("INSERT INTO director (id, name) VALUES (NULL, 'M.Knight.Shamalam')");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_8244BE22899FB366');
        $this->addSql('CREATE TEMPORARY TABLE __temp__film AS SELECT id, director_id, title, description FROM film');
        $this->addSql('DROP TABLE film');
        $this->addSql('CREATE TABLE film (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, director_id INTEGER DEFAULT NULL, title VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO film (id, director_id, title, description) SELECT id, director_id, title, description FROM __temp__film');
        $this->addSql('DROP TABLE __temp__film');
        $this->addSql('CREATE INDEX IDX_8244BE22899FB366 ON film (director_id)');
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
