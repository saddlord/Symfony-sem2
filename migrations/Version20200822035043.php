<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200822035043 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE label DROP label');
        $this->addSql('ALTER TABLE record ADD label_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE record ADD CONSTRAINT FK_9B349F9133B92F39 FOREIGN KEY (label_id) REFERENCES label (id)');
        $this->addSql('CREATE INDEX IDX_9B349F9133B92F39 ON record (label_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE label ADD label VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE record DROP FOREIGN KEY FK_9B349F9133B92F39');
        $this->addSql('DROP INDEX IDX_9B349F9133B92F39 ON record');
        $this->addSql('ALTER TABLE record DROP label_id');
    }
}
