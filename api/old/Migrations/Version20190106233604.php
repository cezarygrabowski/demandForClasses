<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190106233604 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE subjects_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE users_qualifications (user_id INT NOT NULL, qualification_id INT NOT NULL, PRIMARY KEY(user_id, qualification_id))');
        $this->addSql('CREATE INDEX IDX_19DDABF9A76ED395 ON users_qualifications (user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_19DDABF91A75EE38 ON users_qualifications (qualification_id)');
        $this->addSql('CREATE TABLE subjects (id INT NOT NULL, name VARCHAR(255) NOT NULL, shortened_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE users_qualifications ADD CONSTRAINT FK_19DDABF9A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users_qualifications ADD CONSTRAINT FK_19DDABF91A75EE38 FOREIGN KEY (qualification_id) REFERENCES subjects (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE demands ADD subject_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE demands DROP subject');
        $this->addSql('ALTER TABLE demands DROP shortened_subject_name');
        $this->addSql('ALTER TABLE demands ADD CONSTRAINT FK_D24062F423EDC87 FOREIGN KEY (subject_id) REFERENCES subjects (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D24062F423EDC87 ON demands (subject_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE users_qualifications DROP CONSTRAINT FK_19DDABF91A75EE38');
        $this->addSql('ALTER TABLE demands DROP CONSTRAINT FK_D24062F423EDC87');
        $this->addSql('DROP SEQUENCE subjects_id_seq CASCADE');
        $this->addSql('DROP TABLE users_qualifications');
        $this->addSql('DROP TABLE subjects');
        $this->addSql('DROP INDEX IDX_D24062F423EDC87');
        $this->addSql('ALTER TABLE demands ADD subject VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE demands ADD shortened_subject_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE demands DROP subject_id');
    }
}
