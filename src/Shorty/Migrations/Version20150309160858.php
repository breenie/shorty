<?php

namespace Shorty\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150309160858 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $table = $schema->createTable('shorty_url_visit');
        $table->addColumn('shorty_url_id', Type::BIGINT, array('unsigned' => true));
        $table->addColumn('user_agent', Type::STRING, array('length' => 255));
        $table->addColumn('created', Type::DATETIME);
        $table->addIndex(array('shorty_url_id'));
        $table->addIndex(array('created'));
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('shorty_url_visit');
    }
}
