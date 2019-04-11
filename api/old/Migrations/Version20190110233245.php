<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190110233245 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE schedules ADD building_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE schedules DROP building');
        $this->addSql('ALTER TABLE schedules DROP room');
        $this->addSql('ALTER TABLE schedules ADD CONSTRAINT FK_313BDC8E4D2A7E12 FOREIGN KEY (building_id) REFERENCES buildings (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_313BDC8E4D2A7E12 ON schedules (building_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE schedules DROP CONSTRAINT FK_313BDC8E4D2A7E12');
        $this->addSql('DROP INDEX UNIQ_313BDC8E4D2A7E12');
        $this->addSql('ALTER TABLE schedules ADD building VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE schedules ADD room VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE schedules DROP building_id');
    }
}
