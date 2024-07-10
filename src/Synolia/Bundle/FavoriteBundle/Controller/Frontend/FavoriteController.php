<?php

declare(strict_types=1);

namespace Synolia\Bundle\FavoriteBundle\Controller\Frontend;

use Oro\Bundle\LayoutBundle\Attribute\Layout;
use Oro\Bundle\SecurityBundle\Attribute\Acl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Synolia\Bundle\FavoriteBundle\Entity\Favorite;

final class FavoriteController extends AbstractController
{
    #[Route('/', name: 'synolia_favorite_index')]
    #[Layout(vars: ['entity_class'])]
    #[Acl(
        id: 'synolia_favorite_frontend_view',
        type: 'entity',
        class: 'SynoliaFavoriteBundle\Entity\Favorite',
        permission: 'VIEW',
        group_name: 'commerce'
    )]
    public function indexAction(): array
    {
        $entityClass = Favorite::class;

        return ['entity_class' => $entityClass];
    }
}
