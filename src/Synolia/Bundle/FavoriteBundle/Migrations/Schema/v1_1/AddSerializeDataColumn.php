<?php

namespace Synolia\Bundle\FavoriteBundle\Migrations\Schema\v1_1;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Oro\Bundle\MigrationBundle\Migration\Extension\RenameExtension;
use Oro\Bundle\MigrationBundle\Migration\Extension\RenameExtensionAwareInterface;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class AddSerializeDataColumn implements Migration, OrderedMigrationInterface
{
    /**
     * {@inheritDoc}
     */
    public function up(Schema $schema, QueryBag $queries): void
    {
        $table = $schema->getTable('synolia_favorite');
        if ($table->hasColumn('serialized_data')) {
            return;
        }
        $table->addColumn('serialized_data', Types::JSON, ['notnull' => false]);
    }

    public function getOrder(): int
    {
        return 10;
    }
}
