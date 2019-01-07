<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190106133716 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE schedules_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE lecture_types_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE demands_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE schedules (id INT NOT NULL, week_number VARCHAR(255) NOT NULL, suggested_hours VARCHAR(255) NOT NULL, building VARCHAR(255) NOT NULL, room VARCHAR(255) NOT NULL, lecture_type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE lecture_types (id INT NOT NULL, demand_id INT DEFAULT NULL, lecturer_id INT DEFAULT NULL, hours VARCHAR(255) NOT NULL, comments VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F25D5CDE5D022E59 ON lecture_types (demand_id)');
        $this->addSql('CREATE INDEX IDX_F25D5CDEBA2D8762 ON lecture_types (lecturer_id)');
        $this->addSql('CREATE TABLE demands (id INT NOT NULL, year_number VARCHAR(255) NOT NULL, institute VARCHAR(255) NOT NULL, department VARCHAR(255) NOT NULL, "group" VARCHAR(255) NOT NULL, semester VARCHAR(255) NOT NULL, subject VARCHAR(255) NOT NULL, group_type VARCHAR(255) NOT NULL, total_hours VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE lecture_types ADD CONSTRAINT FK_F25D5CDE5D022E59 FOREIGN KEY (demand_id) REFERENCES demands (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lecture_types ADD CONSTRAINT FK_F25D5CDEBA2D8762 FOREIGN KEY (lecturer_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE lecture_types DROP CONSTRAINT FK_F25D5CDE5D022E59');
        $this->addSql('DROP SEQUENCE schedules_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE lecture_types_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE demands_id_seq CASCADE');
        $this->addSql('DROP TABLE schedules');
        $this->addSql('DROP TABLE lecture_types');
        $this->addSql('DROP TABLE demands');
    }
}
