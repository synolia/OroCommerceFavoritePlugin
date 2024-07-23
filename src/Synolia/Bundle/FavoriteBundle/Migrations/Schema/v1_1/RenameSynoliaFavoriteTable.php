<?php

namespace Synolia\Bundle\FavoriteBundle\Migrations\Schema\v1_1;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Extension\RenameExtension;
use Oro\Bundle\MigrationBundle\Migration\Extension\RenameExtensionAwareInterface;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class RenameSynoliaFavoriteTable implements Migration, RenameExtensionAwareInterface, OrderedMigrationInterface
{
    private RenameExtension $renameExtension;

    public function setRenameExtension(RenameExtension $renameExtension): void
    {
        $this->renameExtension = $renameExtension;
    }

    /**
     * {@inheritDoc}
     */
    public function up(Schema $schema, QueryBag $queries): void
    {
        $this->renameExtension->renameTable(
            $schema,
            $queries,
            'sy_favorite',
            'synolia_favorite'
        );
    }

    public function getOrder(): int
    {
        return 0;
    }
}
