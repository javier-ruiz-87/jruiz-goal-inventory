<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181119120340 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE notificacion (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, mensaje VARCHAR(255) NOT NULL, entidad VARCHAR(255) DEFAULT NULL, objeto_nombre VARCHAR(255) DEFAULT NULL, objeto_id INTEGER DEFAULT NULL, fecha DATETIME DEFAULT NULL)');
        $this->addSql('CREATE TABLE articulo (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, tipo VARCHAR(255) DEFAULT NULL, fecha_caducidad DATETIME DEFAULT NULL, disponible BOOLEAN NOT NULL, caducado BOOLEAN DEFAULT \'0\' NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_69E94E913A909126 ON articulo (nombre)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE notificacion');
        $this->addSql('DROP TABLE articulo');
    }
}
