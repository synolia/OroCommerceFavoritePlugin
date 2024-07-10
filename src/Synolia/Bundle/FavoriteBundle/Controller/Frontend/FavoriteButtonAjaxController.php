<?php

declare(strict_types=1);

namespace Synolia\Bundle\FavoriteBundle\Controller\Frontend;

use Oro\Bundle\CustomerBundle\Entity\CustomerUser;
use Oro\Bundle\ProductBundle\Entity\Product;
use Oro\Bundle\SecurityBundle\Authentication\TokenAccessorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Synolia\Bundle\FavoriteBundle\Handler\FavoriteButtonAjaxHandler;

/**
 * @Route("/ajax", options={"expose"=true})
 */
final class FavoriteButtonAjaxController extends AbstractController
{
    #[Route('/update/{id}', requirements: ['id' => '\d+'], name: 'synolia_favorite_button_ajax_update', methods: ['POST'])]
    public function updateAction(
        Product $product,
        TokenAccessorInterface $tokenAccessor,
        FavoriteButtonAjaxHandler $ajaxHandler
    ): JsonResponse {
        /** @var CustomerUser $user */
        $user = $tokenAccessor->getUser();

        return new JsonResponse($ajaxHandler->update($product, $user));
    }
}
