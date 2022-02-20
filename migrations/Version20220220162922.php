<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220220162922 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE qa_access_token_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE qa_provider_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE qa_user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE qa_access_token (id INT NOT NULL, provider_id INT DEFAULT NULL, user_id INT DEFAULT NULL, token VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9A2B95BFA53A8AA ON qa_access_token (provider_id)');
        $this->addSql('CREATE INDEX IDX_9A2B95BFA76ED395 ON qa_access_token (user_id)');
        $this->addSql('CREATE TABLE qa_provider (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE qa_user (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_24F8951E7927C74 ON qa_user (email)');
        $this->addSql('ALTER TABLE qa_access_token ADD CONSTRAINT FK_9A2B95BFA53A8AA FOREIGN KEY (provider_id) REFERENCES qa_provider (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE qa_access_token ADD CONSTRAINT FK_9A2B95BFA76ED395 FOREIGN KEY (user_id) REFERENCES qa_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE qa_access_token DROP CONSTRAINT FK_9A2B95BFA53A8AA');
        $this->addSql('ALTER TABLE qa_access_token DROP CONSTRAINT FK_9A2B95BFA76ED395');
        $this->addSql('DROP SEQUENCE qa_access_token_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE qa_provider_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE qa_user_id_seq CASCADE');
        $this->addSql('DROP TABLE qa_access_token');
        $this->addSql('DROP TABLE qa_provider');
        $this->addSql('DROP TABLE qa_user');
    }
}
