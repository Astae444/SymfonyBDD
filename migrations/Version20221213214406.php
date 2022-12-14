<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221213214406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chaton_proprietaire (chaton_id INT NOT NULL, proprietaire_id INT NOT NULL, INDEX IDX_7060315F640066C9 (chaton_id), INDEX IDX_7060315F76C50E4A (proprietaire_id), PRIMARY KEY(chaton_id, proprietaire_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chaton_proprietaire ADD CONSTRAINT FK_7060315F640066C9 FOREIGN KEY (chaton_id) REFERENCES chaton (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chaton_proprietaire ADD CONSTRAINT FK_7060315F76C50E4A FOREIGN KEY (proprietaire_id) REFERENCES proprietaire (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chaton_proprietaire DROP FOREIGN KEY FK_7060315F640066C9');
        $this->addSql('ALTER TABLE chaton_proprietaire DROP FOREIGN KEY FK_7060315F76C50E4A');
        $this->addSql('DROP TABLE chaton_proprietaire');
    }
}
