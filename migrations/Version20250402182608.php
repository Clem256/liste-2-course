<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250402182608 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE article (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, categorie_id INTEGER NOT NULL, consommateur_id INTEGER DEFAULT NULL, proprietaire_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, quantity INTEGER NOT NULL, type VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, nutriscore VARCHAR(1) DEFAULT NULL, calorie INTEGER DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, unit VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_23A0E66BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_23A0E66865AC8FB FOREIGN KEY (consommateur_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_23A0E6676C50E4A FOREIGN KEY (proprietaire_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_23A0E66BCF5E72D ON article (categorie_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_23A0E66865AC8FB ON article (consommateur_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_23A0E6676C50E4A ON article (proprietaire_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE categorie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE shopping_list (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE shopping_list_user (shopping_list_id INTEGER NOT NULL, user_id INTEGER NOT NULL, PRIMARY KEY(shopping_list_id, user_id), CONSTRAINT FK_DD39317323245BF9 FOREIGN KEY (shopping_list_id) REFERENCES shopping_list (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_DD393173A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_DD39317323245BF9 ON shopping_list_user (shopping_list_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_DD393173A76ED395 ON shopping_list_user (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE shopping_list_article (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, article_id INTEGER NOT NULL, shopping_list_id INTEGER NOT NULL, editeur_id INTEGER NOT NULL, qty INTEGER NOT NULL, CONSTRAINT FK_CFA41AD7294869C FOREIGN KEY (article_id) REFERENCES article (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_CFA41AD23245BF9 FOREIGN KEY (shopping_list_id) REFERENCES shopping_list (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_CFA41AD3375BD21 FOREIGN KEY (editeur_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_CFA41AD7294869C ON shopping_list_article (article_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_CFA41AD23245BF9 ON shopping_list_article (shopping_list_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_CFA41AD3375BD21 ON shopping_list_article (editeur_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE "user" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(255) DEFAULT NULL, is_verified BOOLEAN NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
            )
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , available_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , delivered_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
            )
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE article
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE categorie
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE shopping_list
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE shopping_list_user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE shopping_list_article
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "user"
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');

    }

}
