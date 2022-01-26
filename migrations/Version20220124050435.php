<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220124050435 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE job_position (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE employee ADD job_position_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A1BEE8350F FOREIGN KEY (job_position_id) REFERENCES job_position (id)');
        $this->addSql('CREATE INDEX IDX_5D9F75A1BEE8350F ON employee (job_position_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649AA08CB10 ON user (login)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employee DROP FOREIGN KEY FK_5D9F75A1BEE8350F');
        $this->addSql('DROP TABLE job_position');
        $this->addSql('DROP INDEX IDX_5D9F75A1BEE8350F ON employee');
        $this->addSql('ALTER TABLE employee DROP job_position_id');
        $this->addSql('DROP INDEX UNIQ_8D93D649AA08CB10 ON user');
    }
}
