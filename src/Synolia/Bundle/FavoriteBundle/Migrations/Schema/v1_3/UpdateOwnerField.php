<?php

namespace Synolia\Bundle\FavoriteBundle\Migrations\Schema\v1_3;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\ContactUsBundle\Entity\ContactRequest;
use Oro\Bundle\EntityConfigBundle\Migration\UpdateEntityConfigEntityValueQuery;
use Oro\Bundle\MigrationBundle\Migration\Extension\RenameExtension;
use Oro\Bundle\MigrationBundle\Migration\Extension\RenameExtensionAwareInterface;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Synolia\Bundle\FavoriteBundle\Entity\Favorite;

class UpdateOwnerField implements Migration
{
    public function up(Schema $schema, QueryBag $queries): void
    {
        $queries->addPostQuery(new UpdateEntityConfigEntityValueQuery(
            Favorite::class,
            'ownership',
            'frontend_owner_type',
            'FRONTEND_USER'
        ));
    }
}
