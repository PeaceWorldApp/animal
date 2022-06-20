<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220607133135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE note ADD cat_id INT NOT NULL');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14E6ADA943 FOREIGN KEY (cat_id) REFERENCES cat_note (id)');
        $this->addSql('CREATE INDEX IDX_CFBDFA14E6ADA943 ON note (cat_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14E6ADA943');
        $this->addSql('DROP INDEX IDX_CFBDFA14E6ADA943 ON note');
        $this->addSql('ALTER TABLE note DROP cat_id');
    }
}
