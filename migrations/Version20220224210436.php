<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220224210436 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE qa_invitation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE qa_membership_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE qa_organization_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE qa_invitation (id INT NOT NULL, qa_organization_id INT DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, status SMALLINT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2230E3D216CA7ECE ON qa_invitation (qa_organization_id)');
        $this->addSql('CREATE TABLE qa_membership (id INT NOT NULL, qa_user_id INT DEFAULT NULL, qa_org_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_55D250F571DFEB6 ON qa_membership (qa_user_id)');
        $this->addSql('CREATE INDEX IDX_55D250F58D4C27FB ON qa_membership (qa_org_id)');
        $this->addSql('CREATE TABLE qa_organization (id INT NOT NULL, domain VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE qa_invitation ADD CONSTRAINT FK_2230E3D216CA7ECE FOREIGN KEY (qa_organization_id) REFERENCES qa_organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE qa_membership ADD CONSTRAINT FK_55D250F571DFEB6 FOREIGN KEY (qa_user_id) REFERENCES qa_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE qa_membership ADD CONSTRAINT FK_55D250F58D4C27FB FOREIGN KEY (qa_org_id) REFERENCES qa_organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE qa_invitation DROP CONSTRAINT FK_2230E3D216CA7ECE');
        $this->addSql('ALTER TABLE qa_membership DROP CONSTRAINT FK_55D250F58D4C27FB');
        $this->addSql('DROP SEQUENCE qa_invitation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE qa_membership_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE qa_organization_id_seq CASCADE');
        $this->addSql('DROP TABLE qa_invitation');
        $this->addSql('DROP TABLE qa_membership');
        $this->addSql('DROP TABLE qa_organization');
    }
}
