<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220202085633 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE books CHANGE author author TEXT NOT NULL, CHANGE publisher publisher VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD book_history LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE books CHANGE title title VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE author author LONGTEXT NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE description description VARCHAR(3000) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE publisher publisher VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE category category TEXT NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE image_name image_name VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE user DROP book_history, CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE first_name first_name TEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE last_name last_name TEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE phone_number phone_number VARCHAR(30) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE address address VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
