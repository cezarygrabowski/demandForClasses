<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190110212933 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE buildings_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE rooms_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE buildings (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE rooms (id INT NOT NULL, building_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7CA11A964D2A7E12 ON rooms (building_id)');
        $this->addSql('ALTER TABLE rooms ADD CONSTRAINT FK_7CA11A964D2A7E12 FOREIGN KEY (building_id) REFERENCES buildings (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP INDEX uniq_313bdc8e35e32fcd');
        $this->addSql('CREATE INDEX IDX_313BDC8E35E32FCD ON schedules (lecture_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE rooms DROP CONSTRAINT FK_7CA11A964D2A7E12');
        $this->addSql('DROP SEQUENCE buildings_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE rooms_id_seq CASCADE');
        $this->addSql('DROP TABLE buildings');
        $this->addSql('DROP TABLE rooms');
        $this->addSql('DROP INDEX IDX_313BDC8E35E32FCD');
        $this->addSql('CREATE UNIQUE INDEX uniq_313bdc8e35e32fcd ON schedules (lecture_id)');
    }
}
