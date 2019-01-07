<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190106195946 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE demands ALTER year_number DROP NOT NULL');
        $this->addSql('ALTER TABLE demands ALTER institute DROP NOT NULL');
        $this->addSql('ALTER TABLE demands ALTER department DROP NOT NULL');
        $this->addSql('ALTER TABLE demands ALTER "group" DROP NOT NULL');
        $this->addSql('ALTER TABLE demands ALTER semester DROP NOT NULL');
        $this->addSql('ALTER TABLE demands ALTER subject DROP NOT NULL');
        $this->addSql('ALTER TABLE demands ALTER group_type DROP NOT NULL');
        $this->addSql('ALTER TABLE demands ALTER total_hours DROP NOT NULL');
        $this->addSql('ALTER TABLE demands ALTER shortened_subject_name DROP NOT NULL');
        $this->addSql('ALTER TABLE schedules ALTER week_number DROP NOT NULL');
        $this->addSql('ALTER TABLE schedules ALTER suggested_hours DROP NOT NULL');
        $this->addSql('ALTER TABLE schedules ALTER building DROP NOT NULL');
        $this->addSql('ALTER TABLE schedules ALTER room DROP NOT NULL');
        $this->addSql('ALTER TABLE lecture_types ALTER hours DROP NOT NULL');
        $this->addSql('ALTER TABLE lecture_types ALTER comments DROP NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE schedules ALTER week_number SET NOT NULL');
        $this->addSql('ALTER TABLE schedules ALTER suggested_hours SET NOT NULL');
        $this->addSql('ALTER TABLE schedules ALTER building SET NOT NULL');
        $this->addSql('ALTER TABLE schedules ALTER room SET NOT NULL');
        $this->addSql('ALTER TABLE lecture_types ALTER hours SET NOT NULL');
        $this->addSql('ALTER TABLE lecture_types ALTER comments SET NOT NULL');
        $this->addSql('ALTER TABLE demands ALTER year_number SET NOT NULL');
        $this->addSql('ALTER TABLE demands ALTER institute SET NOT NULL');
        $this->addSql('ALTER TABLE demands ALTER department SET NOT NULL');
        $this->addSql('ALTER TABLE demands ALTER "group" SET NOT NULL');
        $this->addSql('ALTER TABLE demands ALTER semester SET NOT NULL');
        $this->addSql('ALTER TABLE demands ALTER subject SET NOT NULL');
        $this->addSql('ALTER TABLE demands ALTER group_type SET NOT NULL');
        $this->addSql('ALTER TABLE demands ALTER total_hours SET NOT NULL');
        $this->addSql('ALTER TABLE demands ALTER shortened_subject_name SET NOT NULL');
    }
}
