<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221025180916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE teams_users DROP FOREIGN KEY FK_E0BD5D44296CD8AE');
        $this->addSql('ALTER TABLE teams_users ADD CONSTRAINT FK_E0BD5D44296CD8AE FOREIGN KEY (team_id) REFERENCES teams (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tickets_users DROP FOREIGN KEY FK_ECE47056700047D2');
        $this->addSql('ALTER TABLE tickets_users ADD CONSTRAINT FK_ECE47056700047D2 FOREIGN KEY (ticket_id) REFERENCES tickets (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE teams_users DROP FOREIGN KEY FK_E0BD5D44296CD8AE');
        $this->addSql('ALTER TABLE teams_users ADD CONSTRAINT FK_E0BD5D44296CD8AE FOREIGN KEY (team_id) REFERENCES teams (id)');
        $this->addSql('ALTER TABLE tickets_users DROP FOREIGN KEY FK_ECE47056700047D2');
        $this->addSql('ALTER TABLE tickets_users ADD CONSTRAINT FK_ECE47056700047D2 FOREIGN KEY (ticket_id) REFERENCES tickets (id)');
    }
}
