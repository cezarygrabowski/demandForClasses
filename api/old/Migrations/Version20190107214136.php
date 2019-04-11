<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190107214136 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE lectures_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE lectures (id INT NOT NULL, demand_id INT DEFAULT NULL, lecturer_id INT DEFAULT NULL, hours VARCHAR(255) DEFAULT NULL, comments VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_63C861D05D022E59 ON lectures (demand_id)');
        $this->addSql('CREATE INDEX IDX_63C861D0BA2D8762 ON lectures (lecturer_id)');
        $this->addSql('ALTER TABLE lectures ADD CONSTRAINT FK_63C861D05D022E59 FOREIGN KEY (demand_id) REFERENCES demands (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lectures ADD CONSTRAINT FK_63C861D0BA2D8762 FOREIGN KEY (lecturer_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE schedules DROP CONSTRAINT fk_313bdc8ef308dfc7');
        $this->addSql('DROP INDEX idx_313bdc8ef308dfc7');
        $this->addSql('ALTER TABLE schedules RENAME COLUMN lecture_type_id TO lecture_id');
        $this->addSql('ALTER TABLE schedules ADD CONSTRAINT FK_313BDC8E35E32FCD FOREIGN KEY (lecture_id) REFERENCES lectures (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_313BDC8E35E32FCD ON schedules (lecture_id)');
        $this->addSql('ALTER TABLE lecture_types DROP CONSTRAINT fk_f25d5cde5d022e59');
        $this->addSql('ALTER TABLE lecture_types DROP CONSTRAINT fk_f25d5cdeba2d8762');
        $this->addSql('DROP INDEX idx_f25d5cde5d022e59');
        $this->addSql('DROP INDEX idx_f25d5cdeba2d8762');
        $this->addSql('ALTER TABLE lecture_types ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE lecture_types DROP demand_id');
        $this->addSql('ALTER TABLE lecture_types DROP lecturer_id');
        $this->addSql('ALTER TABLE lecture_types DROP hours');
        $this->addSql('ALTER TABLE lecture_types DROP comments');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE schedules DROP CONSTRAINT FK_313BDC8E35E32FCD');
        $this->addSql('DROP SEQUENCE lectures_id_seq CASCADE');
        $this->addSql('DROP TABLE lectures');
        $this->addSql('DROP INDEX IDX_313BDC8E35E32FCD');
        $this->addSql('ALTER TABLE schedules RENAME COLUMN lecture_id TO lecture_type_id');
        $this->addSql('ALTER TABLE schedules ADD CONSTRAINT fk_313bdc8ef308dfc7 FOREIGN KEY (lecture_type_id) REFERENCES lecture_types (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_313bdc8ef308dfc7 ON schedules (lecture_type_id)');
        $this->addSql('ALTER TABLE lecture_types ADD demand_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lecture_types ADD lecturer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lecture_types ADD hours VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE lecture_types ADD comments VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE lecture_types DROP name');
        $this->addSql('ALTER TABLE lecture_types ADD CONSTRAINT fk_f25d5cde5d022e59 FOREIGN KEY (demand_id) REFERENCES demands (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lecture_types ADD CONSTRAINT fk_f25d5cdeba2d8762 FOREIGN KEY (lecturer_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_f25d5cde5d022e59 ON lecture_types (demand_id)');
        $this->addSql('CREATE INDEX idx_f25d5cdeba2d8762 ON lecture_types (lecturer_id)');
    }
}
