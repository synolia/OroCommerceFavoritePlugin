<?php

declare(strict_types=1);

namespace Synolia\Bundle\FavoriteBundle\Twig;

use Doctrine\ORM\EntityManager;
use Oro\Bundle\ProductBundle\Entity\Product;
use Oro\Bundle\ProductBundle\Model\ProductView;
use Synolia\Bundle\FavoriteBundle\Layout\DataProvider\FavoriteDataProvider;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FavoriteExtension extends AbstractExtension
{
    /** @var FavoriteDataProvider */
    protected $favoriteDataProvider;

    /** @var EntityManager */
    protected $entityManager;

    public function __construct(
        FavoriteDataProvider $favoriteDataProvider,
        EntityManager $entityManager
    ) {
        $this->favoriteDataProvider = $favoriteDataProvider;
        $this->entityManager = $entityManager;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction(
                'is_favorite_product',
                [$this, 'isFavoriteProduct']
            )
        ];
    }

    /**
     * @param Product|array|ProductView $product
     * @return bool
     */
    public function isFavoriteProduct(Product|array|ProductView $product): bool
    {
        if ($product instanceof ProductView) {
            return $product->get('favorite');
        }

        if (\is_array($product)) {
            return $product['favorite'] ?? false;
        }

        return $this->favoriteDataProvider->isProductFavorite($product);
    }
}
