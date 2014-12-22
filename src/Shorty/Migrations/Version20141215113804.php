<?php

namespace Shorty\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141215113804 extends AbstractMigration
{
    /**
     * Up ports the database.
     *
     * @param Schema $schema
     *
     * @throws \Doctrine\DBAL\Schema\SchemaException
     */
    public function up(Schema $schema)
    {
        $table = $schema->createTable('shorty_url');
        $table->addColumn('id', Type::BIGINT, array('auto_increment' => true, 'unsigned' => true));
        $table->addColumn('url', Type::STRING, array('length' => 2083));
        $table->addColumn('created', Type::DATETIME);
        $table->setPrimaryKey(array('id'));
    }

    /**
     * Down ports the database.
     *
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('shorty_url');
    }
}
