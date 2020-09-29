<?php

namespace Database\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20200929180848 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE freelancer (id VARCHAR(255) NOT NULL, remember_token VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, hour_rate INT NOT NULL, UNIQUE INDEX UNIQ_4C2ED1E8E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proposals (id VARCHAR(255) NOT NULL, job_id VARCHAR(255) DEFAULT NULL, freelancer_id VARCHAR(255) DEFAULT NULL, cover_letter VARCHAR(255) NOT NULL, estimated_time INT NOT NULL, proposal_status INT NOT NULL, INDEX IDX_A5BA3A8FBE04EA9 (job_id), INDEX IDX_A5BA3A8F8545BDF5 (freelancer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customers (id VARCHAR(255) NOT NULL, remember_token VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_62534E21E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jobs (id VARCHAR(255) NOT NULL, customer_id VARCHAR(255) DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, job_status INT NOT NULL, INDEX IDX_A8936DC59395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE proposals ADD CONSTRAINT FK_A5BA3A8FBE04EA9 FOREIGN KEY (job_id) REFERENCES jobs (id)');
        $this->addSql('ALTER TABLE proposals ADD CONSTRAINT FK_A5BA3A8F8545BDF5 FOREIGN KEY (freelancer_id) REFERENCES freelancer (id)');
        $this->addSql('ALTER TABLE jobs ADD CONSTRAINT FK_A8936DC59395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE proposals DROP FOREIGN KEY FK_A5BA3A8F8545BDF5');
        $this->addSql('ALTER TABLE jobs DROP FOREIGN KEY FK_A8936DC59395C3F3');
        $this->addSql('ALTER TABLE proposals DROP FOREIGN KEY FK_A5BA3A8FBE04EA9');
        $this->addSql('DROP TABLE freelancer');
        $this->addSql('DROP TABLE proposals');
        $this->addSql('DROP TABLE customers');
        $this->addSql('DROP TABLE jobs');
    }
}
