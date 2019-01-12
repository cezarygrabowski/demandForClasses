<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190112155046 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP INDEX uniq_313bdc8e4d2a7e12');
        $this->addSql('DROP INDEX uniq_313bdc8e54177093');
        $this->addSql('CREATE INDEX IDX_313BDC8E4D2A7E12 ON schedules (building_id)');
        $this->addSql('CREATE INDEX IDX_313BDC8E54177093 ON schedules (room_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX IDX_313BDC8E4D2A7E12');
        $this->addSql('DROP INDEX IDX_313BDC8E54177093');
        $this->addSql('CREATE UNIQUE INDEX uniq_313bdc8e4d2a7e12 ON schedules (building_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_313bdc8e54177093 ON schedules (room_id)');
    }
}
