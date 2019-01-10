<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190107213333 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE schedules ADD lecture_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE schedules DROP lecture_type');
        $this->addSql('ALTER TABLE schedules ADD CONSTRAINT FK_313BDC8EF308DFC7 FOREIGN KEY (lecture_type_id) REFERENCES lecture_types (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_313BDC8EF308DFC7 ON schedules (lecture_type_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE schedules DROP CONSTRAINT FK_313BDC8EF308DFC7');
        $this->addSql('DROP INDEX IDX_313BDC8EF308DFC7');
        $this->addSql('ALTER TABLE schedules ADD lecture_type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE schedules DROP lecture_type_id');
    }
}
