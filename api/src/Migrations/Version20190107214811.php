<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190107214811 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP INDEX idx_313bdc8e35e32fcd');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_313BDC8E35E32FCD ON schedules (lecture_id)');
        $this->addSql('ALTER TABLE lectures ADD lecture_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lectures ADD CONSTRAINT FK_63C861D0F308DFC7 FOREIGN KEY (lecture_type_id) REFERENCES lecture_types (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_63C861D0F308DFC7 ON lectures (lecture_type_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP INDEX UNIQ_313BDC8E35E32FCD');
        $this->addSql('CREATE INDEX idx_313bdc8e35e32fcd ON schedules (lecture_id)');
        $this->addSql('ALTER TABLE lectures DROP CONSTRAINT FK_63C861D0F308DFC7');
        $this->addSql('DROP INDEX IDX_63C861D0F308DFC7');
        $this->addSql('ALTER TABLE lectures DROP lecture_type_id');
    }
}
