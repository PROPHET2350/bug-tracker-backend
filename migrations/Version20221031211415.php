<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221031211415 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projects_users DROP FOREIGN KEY FK_8102F1D7166D1F9C');
        $this->addSql('ALTER TABLE projects_users ADD CONSTRAINT FK_8102F1D7166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projects_users DROP FOREIGN KEY FK_8102F1D7166D1F9C');
        $this->addSql('ALTER TABLE projects_users ADD CONSTRAINT FK_8102F1D7166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
    }
}
