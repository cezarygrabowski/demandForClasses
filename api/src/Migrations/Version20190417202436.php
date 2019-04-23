<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190417202436 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE users DROP CONSTRAINT fk_1483a5e9abfe1c6f');
        $this->addSql('DROP INDEX idx_1483a5e9abfe1c6f');
        $this->addSql('ALTER TABLE users RENAME COLUMN user_uuid TO imported_by_uuid');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E933EC5544 FOREIGN KEY (imported_by_uuid) REFERENCES users (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1483A5E933EC5544 ON users (imported_by_uuid)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E933EC5544');
        $this->addSql('DROP INDEX IDX_1483A5E933EC5544');
        $this->addSql('ALTER TABLE users RENAME COLUMN imported_by_uuid TO user_uuid');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT fk_1483a5e9abfe1c6f FOREIGN KEY (user_uuid) REFERENCES users (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_1483a5e9abfe1c6f ON users (user_uuid)');
    }
}
