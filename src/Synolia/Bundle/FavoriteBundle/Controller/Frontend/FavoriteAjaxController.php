<?php

declare(strict_types=1);

namespace Synolia\Bundle\FavoriteBundle\Controller\Frontend;

use Doctrine\ORM\EntityManagerInterface;
use Oro\Bundle\ProductBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Synolia\Bundle\FavoriteBundle\Handler\FavoriteAjaxHandler;

class FavoriteAjaxController extends AbstractController
{
    public function __construct(
        protected FavoriteAjaxHandler $favoriteAjaxHandler,
        protected TranslatorInterface $translator
    ) {
    }

    /**
     * @Route(
     *     "/create/{id}",
     *     requirements={"id"="\d+"},
     *     name="synolia_favorite_button_ajax_update"
     *     )
     *
     * @param Product $product
     *
     * @return JsonResponse
     */
    public function create(Product $product): JsonResponse
    {
        $result = $this->favoriteAjaxHandler->create($product);
        if ($result['success']) {
            return new JsonResponse($result);
        }

        return new JsonResponse([
            'status' => 'warning',
            'message' => $this->translator->trans(
                'synolia_favorite_bundle.controller.logged_in'
            )
        ]);
    }
}
