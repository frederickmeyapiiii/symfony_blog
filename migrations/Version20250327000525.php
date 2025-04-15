<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250327000525 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, article_id INT DEFAULT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_67F068BC7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC7294869C FOREIGN KEY (article_id) REFERENCES article (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD email VARCHAR(255) NOT NULL, CHANGE content message LONGTEXT NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC7294869C
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE commentaire
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP email, CHANGE message content LONGTEXT NOT NULL
        SQL);
    }
}
