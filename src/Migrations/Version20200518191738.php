<?php

declare(strict_types=1);

namespace Aleksbrgt\Balances\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200518191738 extends AbstractMigration
{
    /**
     * @inheritDoc
     */
    public function getDescription() : string
    {
        return 'Create the address table';
    }

    /**
     * @inheritDoc
     */
    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE address (id UUID NOT NULL, currency SMALLINT NOT NULL, address VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
    }

    /**
     * @inheritDoc
     */
    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE address');
    }
}
