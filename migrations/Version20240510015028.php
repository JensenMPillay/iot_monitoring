<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240510015028 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE log (id BINARY(16) NOT NULL, event VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, sensor_id BINARY(16) NOT NULL, INDEX IDX_8F3F68C5A247991F (sensor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE module (id BINARY(16) NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE sensor (id BINARY(16) NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, unit VARCHAR(255) NOT NULL, uptime DATETIME DEFAULT NULL, data_sent_count INT DEFAULT NULL, status TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, module_id BINARY(16) DEFAULT NULL, INDEX IDX_BC8617B0AFC2B591 (module_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE log ADD CONSTRAINT FK_8F3F68C5A247991F FOREIGN KEY (sensor_id) REFERENCES sensor (id)');
        $this->addSql('ALTER TABLE sensor ADD CONSTRAINT FK_BC8617B0AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE log DROP FOREIGN KEY FK_8F3F68C5A247991F');
        $this->addSql('ALTER TABLE sensor DROP FOREIGN KEY FK_BC8617B0AFC2B591');
        $this->addSql('DROP TABLE log');
        $this->addSql('DROP TABLE module');
        $this->addSql('DROP TABLE sensor');
    }
}
