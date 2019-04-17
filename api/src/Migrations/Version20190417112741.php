<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190417112741 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE demands (uuid VARCHAR(255) NOT NULL, subject_uuid VARCHAR(255) DEFAULT NULL, group_uuid VARCHAR(255) DEFAULT NULL, imported_by_uuid VARCHAR(255) DEFAULT NULL, exported_by_uuid VARCHAR(255) DEFAULT NULL, status INT NOT NULL, school_year VARCHAR(255) NOT NULL, semester VARCHAR(255) NOT NULL, institute VARCHAR(255) NOT NULL, department VARCHAR(255) NOT NULL, exported_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, imported_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE INDEX IDX_D24062F443B5D47D ON demands (subject_uuid)');
        $this->addSql('CREATE INDEX IDX_D24062F4F8250BD6 ON demands (group_uuid)');
        $this->addSql('CREATE INDEX IDX_D24062F433EC5544 ON demands (imported_by_uuid)');
        $this->addSql('CREATE INDEX IDX_D24062F4E32BA748 ON demands (exported_by_uuid)');
        $this->addSql('CREATE TABLE subjects (uuid VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, short_name VARCHAR(255) NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE TABLE lecture_sets (uuid VARCHAR(255) NOT NULL, lecturer_uuid VARCHAR(255) DEFAULT NULL, demand_uuid VARCHAR(255) DEFAULT NULL, assigned_by_uuid VARCHAR(255) DEFAULT NULL, lecture_type INT NOT NULL, notes VARCHAR(255) NOT NULL, hours_to_distribute INT NOT NULL, assigned_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE INDEX IDX_2865F2FE9C05E3BB ON lecture_sets (lecturer_uuid)');
        $this->addSql('CREATE INDEX IDX_2865F2FEFBDF0E0 ON lecture_sets (demand_uuid)');
        $this->addSql('CREATE INDEX IDX_2865F2FEED88B194 ON lecture_sets (assigned_by_uuid)');
        $this->addSql('CREATE TABLE weeks (uuid VARCHAR(255) NOT NULL, lecture_set_uuid VARCHAR(255) DEFAULT NULL, place_uuid VARCHAR(255) DEFAULT NULL, number INT NOT NULL, allocated_hours INT NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE INDEX IDX_803157D228886391 ON weeks (lecture_set_uuid)');
        $this->addSql('CREATE INDEX IDX_803157D21BCA204A ON weeks (place_uuid)');
        $this->addSql('CREATE TABLE groups (uuid VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, type INT NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE TABLE places (uuid VARCHAR(255) NOT NULL, building INT NOT NULL, room INT NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE TABLE qualifications (uuid VARCHAR(255) NOT NULL, user_uuid VARCHAR(255) DEFAULT NULL, subject_uuid VARCHAR(255) DEFAULT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE INDEX IDX_6765324CABFE1C6F ON qualifications (user_uuid)');
        $this->addSql('CREATE INDEX IDX_6765324C43B5D47D ON qualifications (subject_uuid)');
        $this->addSql('CREATE TABLE calendars (uuid VARCHAR(255) NOT NULL, semester VARCHAR(255) NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE TABLE users (uuid VARCHAR(255) NOT NULL, calendar_uuid VARCHAR(255) DEFAULT NULL, user_uuid VARCHAR(255) DEFAULT NULL, username VARCHAR(255) NOT NULL, roles JSON NOT NULL, imported_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, api_token VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E983E9D3F2 ON users (calendar_uuid)');
        $this->addSql('CREATE INDEX IDX_1483A5E9ABFE1C6F ON users (user_uuid)');
        $this->addSql('CREATE TABLE months (uuid VARCHAR(255) NOT NULL, calendar_uuid VARCHAR(255) DEFAULT NULL, month_number INT NOT NULL, working_hours INT NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE INDEX IDX_F2E3DC2E83E9D3F2 ON months (calendar_uuid)');
        $this->addSql('ALTER TABLE demands ADD CONSTRAINT FK_D24062F443B5D47D FOREIGN KEY (subject_uuid) REFERENCES subjects (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE demands ADD CONSTRAINT FK_D24062F4F8250BD6 FOREIGN KEY (group_uuid) REFERENCES groups (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE demands ADD CONSTRAINT FK_D24062F433EC5544 FOREIGN KEY (imported_by_uuid) REFERENCES users (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE demands ADD CONSTRAINT FK_D24062F4E32BA748 FOREIGN KEY (exported_by_uuid) REFERENCES users (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lecture_sets ADD CONSTRAINT FK_2865F2FE9C05E3BB FOREIGN KEY (lecturer_uuid) REFERENCES users (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lecture_sets ADD CONSTRAINT FK_2865F2FEFBDF0E0 FOREIGN KEY (demand_uuid) REFERENCES demands (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lecture_sets ADD CONSTRAINT FK_2865F2FEED88B194 FOREIGN KEY (assigned_by_uuid) REFERENCES users (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE weeks ADD CONSTRAINT FK_803157D228886391 FOREIGN KEY (lecture_set_uuid) REFERENCES lecture_sets (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE weeks ADD CONSTRAINT FK_803157D21BCA204A FOREIGN KEY (place_uuid) REFERENCES places (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE qualifications ADD CONSTRAINT FK_6765324CABFE1C6F FOREIGN KEY (user_uuid) REFERENCES users (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE qualifications ADD CONSTRAINT FK_6765324C43B5D47D FOREIGN KEY (subject_uuid) REFERENCES subjects (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E983E9D3F2 FOREIGN KEY (calendar_uuid) REFERENCES calendars (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9ABFE1C6F FOREIGN KEY (user_uuid) REFERENCES users (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE months ADD CONSTRAINT FK_F2E3DC2E83E9D3F2 FOREIGN KEY (calendar_uuid) REFERENCES calendars (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE lecture_sets DROP CONSTRAINT FK_2865F2FEFBDF0E0');
        $this->addSql('ALTER TABLE demands DROP CONSTRAINT FK_D24062F443B5D47D');
        $this->addSql('ALTER TABLE qualifications DROP CONSTRAINT FK_6765324C43B5D47D');
        $this->addSql('ALTER TABLE weeks DROP CONSTRAINT FK_803157D228886391');
        $this->addSql('ALTER TABLE demands DROP CONSTRAINT FK_D24062F4F8250BD6');
        $this->addSql('ALTER TABLE weeks DROP CONSTRAINT FK_803157D21BCA204A');
        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E983E9D3F2');
        $this->addSql('ALTER TABLE months DROP CONSTRAINT FK_F2E3DC2E83E9D3F2');
        $this->addSql('ALTER TABLE demands DROP CONSTRAINT FK_D24062F433EC5544');
        $this->addSql('ALTER TABLE demands DROP CONSTRAINT FK_D24062F4E32BA748');
        $this->addSql('ALTER TABLE lecture_sets DROP CONSTRAINT FK_2865F2FE9C05E3BB');
        $this->addSql('ALTER TABLE lecture_sets DROP CONSTRAINT FK_2865F2FEED88B194');
        $this->addSql('ALTER TABLE qualifications DROP CONSTRAINT FK_6765324CABFE1C6F');
        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E9ABFE1C6F');
        $this->addSql('DROP TABLE demands');
        $this->addSql('DROP TABLE subjects');
        $this->addSql('DROP TABLE lecture_sets');
        $this->addSql('DROP TABLE weeks');
        $this->addSql('DROP TABLE groups');
        $this->addSql('DROP TABLE places');
        $this->addSql('DROP TABLE qualifications');
        $this->addSql('DROP TABLE calendars');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE months');
    }
}
