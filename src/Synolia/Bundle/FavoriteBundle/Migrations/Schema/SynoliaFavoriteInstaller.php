<?php

declare(strict_types=1);

namespace Synolia\Bundle\FavoriteBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class SynoliaFavoriteInstaller implements Installation
{
    /**
     * {@inheritdoc}
     */
    public function getMigrationVersion()
    {
        return 'v1_0';
    }

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        /** Tables generation **/
        $this->createSyFavoriteTable($schema);

        /** Foreign keys generation **/
        $this->addSyFavoriteForeignKeys($schema);
    }

    /**
     * Create sy_favorite table
     *
     * @param Schema $schema
     */
    protected function createSyFavoriteTable(Schema $schema)
    {
        $table = $schema->createTable('sy_favorite');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('product_id', 'integer', ['notnull' => false]);
        $table->addColumn('organization_id', 'integer', ['notnull' => false]);
        $table->addColumn('customer_user_id', 'integer', ['notnull' => false]);
        $table->addColumn('customer_id', 'integer', ['notnull' => false]);
        $table->addColumn('created_at', 'datetime', ['length' => 0, 'comment' => '(DC2Type:datetime)']);
        $table->addColumn('updated_at', 'datetime', ['length' => 0, 'comment' => '(DC2Type:datetime)']);
        $table->addIndex(['organization_id'], 'IDX_A617F45B32C8A3DE', []);
        $table->addIndex(['customer_id'], 'IDX_A617F45B9395C3F3', []);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['customer_user_id'], 'IDX_A617F45BBBB3772B', []);
        $table->addIndex(['product_id'], 'IDX_A617F45B4584665A', []);
    }

    /**
     * Add sy_favorite foreign keys.
     *
     * @param Schema $schema
     */
    protected function addSyFavoriteForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('sy_favorite');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_organization'),
            ['organization_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_product'),
            ['product_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_customer'),
            ['customer_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_customer_user'),
            ['customer_user_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
    }
}
