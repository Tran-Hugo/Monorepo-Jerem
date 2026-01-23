<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260103174059 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // 1. Ajouter la colonne slug (nullable temporairement)
        $this->addSql('ALTER TABLE product ADD slug VARCHAR(255) DEFAULT NULL');

        // 2. Générer un slug pour chaque produit existant
        // Remplace les espaces par des tirets, met en minuscule
        $this->addSql("
            UPDATE product
            SET slug = LOWER(REPLACE(title, ' ', '-'))
        ");

        // 3. Rendre la colonne NOT NULL
        $this->addSql("ALTER TABLE product MODIFY slug VARCHAR(255) NOT NULL");

        // 4. Ajouter l’unique index
        $this->addSql('CREATE UNIQUE INDEX UNIQ_PRODUCT_SLUG ON product (slug)');

    }


    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_PRODUCT_SLUG ON product');
        $this->addSql('ALTER TABLE product DROP slug');
    }
}
