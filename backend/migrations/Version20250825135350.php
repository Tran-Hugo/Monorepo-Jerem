<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250825135350 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shipping_method ADD image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shipping_method ADD CONSTRAINT FK_7503FF2F3DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7503FF2F3DA5256D ON shipping_method (image_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shipping_method DROP FOREIGN KEY FK_7503FF2F3DA5256D');
        $this->addSql('DROP INDEX UNIQ_7503FF2F3DA5256D ON shipping_method');
        $this->addSql('ALTER TABLE shipping_method DROP image_id');
    }
}
