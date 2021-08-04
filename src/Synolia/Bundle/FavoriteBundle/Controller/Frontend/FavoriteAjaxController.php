<?php

declare(strict_types=1);

namespace Synolia\Bundle\FavoriteBundle\Controller\Frontend;

use Oro\Bundle\ProductBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Synolia\Bundle\FavoriteBundle\Handler\FavoriteAjaxHandler;

class FavoriteAjaxController extends AbstractController
{
    /**
     * @Route("/create/{id}", requirements={"id"="\d+"})
     * @ParamConverter("product", class="OroProductBundle:Product", options={"id" = "id"})
     */
    public function create(Product $product): JsonResponse
    {
        /** @var TranslatorInterface */
        $translator = $this->get('translator');
        /** @var FavoriteAjaxHandler $handler */
        $handler = $this->get(FavoriteAjaxHandler::class);

        $result = $handler->create($product);
        if ($result['success']) {
            return new JsonResponse($result);
        }

        return new JsonResponse([
            'status' => 'warning',
            'message' => $translator->trans(
                'synolia_favorite_bundle.controller.logged_in'
            )
        ]);
    }
}
