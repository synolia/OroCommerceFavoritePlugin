<?php

declare(strict_types=1);

namespace Synolia\Bundle\FavoriteBundle\Twig;

use Oro\Bundle\ProductBundle\Entity\Product;
use Oro\Bundle\ProductBundle\Model\ProductView;
use Synolia\Bundle\FavoriteBundle\Layout\DataProvider\FavoriteDataProvider;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FavoriteExtension extends AbstractExtension
{
    public function __construct(
        private readonly FavoriteDataProvider $favoriteDataProvider,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'is_favorite_product',
                [$this, 'isFavoriteProduct']
            ),
        ];
    }

    public function isFavoriteProduct(Product|array|ProductView $product): bool
    {
        if ($product instanceof ProductView) {
            return $product->get('favorite');
        }

        if (\is_array($product)) {
            return $product['favorite'] ?? false;
        }

        return $this->favoriteDataProvider->getProductFavorite($product);
    }
}
