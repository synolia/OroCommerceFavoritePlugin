<?php

declare(strict_types=1);

namespace Synolia\Bundle\FavoriteBundle\Controller\Frontend;

use Oro\Bundle\LayoutBundle\Attribute\Layout;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Synolia\Bundle\FavoriteBundle\Entity\Favorite;

class FavoriteController extends AbstractController
{
    #[\Symfony\Component\Routing\Attribute\Route(path: '/', name: 'synolia_favorite_view')]
    #[Layout(vars: ['entity_class'])]
    public function indexAction(): array
    {
        $entityClass = Favorite::class;
        return [
            'entity_class' => $entityClass
        ];
    }
}
