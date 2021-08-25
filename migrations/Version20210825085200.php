<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210825085200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO genre VALUES (NULL, "Horror");');
        $this->addSql('INSERT INTO genre VALUES (NULL, "Drama");');
        $this->addSql('INSERT INTO genre VALUES (NULL, "Comedy");');
    }

    public function down(Schema $schema): void
    {

    }
}
