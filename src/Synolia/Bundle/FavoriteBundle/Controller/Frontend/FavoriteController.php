<?php

declare(strict_types=1);

namespace Synolia\Bundle\FavoriteBundle\Controller\Frontend;

use Oro\Bundle\LayoutBundle\Annotation\Layout;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Synolia\Bundle\FavoriteBundle\Entity\Favorite;

class FavoriteController extends AbstractController
{
    /**
     * @Route("/", name="synolia_favorite_view")
     * @Layout(vars={"entity_class"})
     */
    public function indexAction(): array
    {
        $entityClass = Favorite::class;
        return [
            'entity_class' => $entityClass
        ];
    }
}
