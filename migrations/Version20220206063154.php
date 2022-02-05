<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220206063154 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE employee (id INT AUTO_INCREMENT NOT NULL, people_id INT NOT NULL, role_id INT DEFAULT NULL, job_position_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_5D9F75A13147C936 (people_id), INDEX IDX_5D9F75A1D60322AC (role_id), INDEX IDX_5D9F75A1BEE8350F (job_position_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job_position (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE people (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(128) NOT NULL, family VARCHAR(128) NOT NULL, patronymic VARCHAR(128) NOT NULL, birthday DATE NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(13) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, employee_id INT NOT NULL, login VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, activated TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649AA08CB10 (login), UNIQUE INDEX UNIQ_8D93D6498C03F15C (employee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A13147C936 FOREIGN KEY (people_id) REFERENCES people (id)');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A1D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A1BEE8350F FOREIGN KEY (job_position_id) REFERENCES job_position (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6498C03F15C');
        $this->addSql('ALTER TABLE employee DROP FOREIGN KEY FK_5D9F75A1BEE8350F');
        $this->addSql('ALTER TABLE employee DROP FOREIGN KEY FK_5D9F75A13147C936');
        $this->addSql('ALTER TABLE employee DROP FOREIGN KEY FK_5D9F75A1D60322AC');
        $this->addSql('DROP TABLE employee');
        $this->addSql('DROP TABLE job_position');
        $this->addSql('DROP TABLE people');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE user');
    }
}
