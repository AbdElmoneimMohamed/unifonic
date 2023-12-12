<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231212081411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE campaigns DROP is_notified, CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE name message VARCHAR(255) NOT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E3737470B6BD307F ON campaigns (message)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE campaigns MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX UNIQ_E3737470B6BD307F ON campaigns');
        $this->addSql('DROP INDEX `primary` ON campaigns');
        $this->addSql('ALTER TABLE campaigns ADD is_notified TINYINT(1) DEFAULT NULL, CHANGE id id INT NOT NULL, CHANGE message name VARCHAR(255) NOT NULL');
    }
}
