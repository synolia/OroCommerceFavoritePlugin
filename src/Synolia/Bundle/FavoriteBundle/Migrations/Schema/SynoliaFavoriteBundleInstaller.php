<?php

declare(strict_types=1);

namespace Synolia\Bundle\FavoriteBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Extension\RenameExtensionAwareInterface;
use Oro\Bundle\MigrationBundle\Migration\Extension\RenameExtensionAwareTrait;
use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */
class SynoliaFavoriteBundleInstaller implements Installation
{

    public function getMigrationVersion(): string
    {
        return 'v1_2';
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function up(Schema $schema, QueryBag $queries): void
    {
        // Tables generation
        $this->createSynoliaFavoriteTable($schema);

        // Foreign keys generation
        $this->addSynoliaFavoriteForeignKeys($schema);
    }

    protected function createSynoliaFavoriteTable(Schema $schema): void
    {
        $table = $schema->createTable('synolia_favorite');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('product_id', 'integer', ['notnull' => false]);
        $table->addColumn('customer_user_id', 'integer', ['notnull' => false]);
        $table->addColumn('customer_id', 'integer', ['notnull' => false]);
        $table->addColumn('organization_id', 'integer', ['notnull' => false]);
        $table->addColumn('created_at', 'datetime', []);
        $table->addColumn('updated_at', 'datetime', []);
        $table->addIndex(['product_id'], 'idx_9c166dd24584665a', []);
        $table->addIndex(['customer_user_id'], 'idx_9c166dd2bbb3772b', []);
        $table->addIndex(['organization_id'], 'idx_9c166dd232c8a3de', []);
        $table->addIndex(['customer_id'], 'idx_9c166dd29395c3f3', []);
        $table->setPrimaryKey(['id']);
    }

    protected function addSynoliaFavoriteForeignKeys(Schema $schema): void
    {
        $table = $schema->getTable('sy_favorite');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_organization'),
            ['organization_id'],
            ['id'],
            ['onUpdate' => null, 'onDelete' => 'SET NULL']
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_product'),
            ['product_id'],
            ['id'],
            ['onUpdate' => null, 'onDelete' => 'CASCADE']
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_customer'),
            ['customer_id'],
            ['id'],
            ['onUpdate' => null, 'onDelete' => 'SET NULL']
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_customer_user'),
            ['customer_user_id'],
            ['id'],
            ['onUpdate' => null, 'onDelete' => 'SET NULL']
        );
    }
}
