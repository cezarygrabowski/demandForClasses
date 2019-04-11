<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190118192339 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE demands ADD groupName VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE demands DROP "group"');
        $this->addSql('ALTER TABLE demands DROP total_hours');
        $this->addSql('ALTER TABLE users ALTER username TYPE VARCHAR(50)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE users ALTER username TYPE VARCHAR(25)');
        $this->addSql('ALTER TABLE demands ADD total_hours VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE demands RENAME COLUMN groupname TO "group"');
    }
}
