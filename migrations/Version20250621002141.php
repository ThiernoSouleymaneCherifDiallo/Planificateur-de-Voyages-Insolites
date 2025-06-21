<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250621002141 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE planification (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, destination_id INT NOT NULL, date_depart DATE NOT NULL, date_retour DATE NOT NULL, notes VARCHAR(255) DEFAULT NULL, statut VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', budget_estime NUMERIC(10, 2) DEFAULT NULL, accompagnants VARCHAR(255) DEFAULT NULL, activites LONGTEXT DEFAULT NULL, hebergement VARCHAR(255) DEFAULT NULL, transport VARCHAR(255) DEFAULT NULL, INDEX IDX_FFC02E1BA76ED395 (user_id), INDEX IDX_FFC02E1B816C6140 (destination_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE planification ADD CONSTRAINT FK_FFC02E1BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE planification ADD CONSTRAINT FK_FFC02E1B816C6140 FOREIGN KEY (destination_id) REFERENCES destination (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE planification DROP FOREIGN KEY FK_FFC02E1BA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE planification DROP FOREIGN KEY FK_FFC02E1B816C6140
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE planification
        SQL);
    }
}
