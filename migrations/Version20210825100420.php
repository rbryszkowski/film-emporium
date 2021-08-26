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
    }
}
