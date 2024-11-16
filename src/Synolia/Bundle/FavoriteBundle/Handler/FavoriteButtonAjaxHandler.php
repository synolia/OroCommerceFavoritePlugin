<?php

declare(strict_types=1);

namespace Synolia\Bundle\FavoriteBundle\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Oro\Bundle\CustomerBundle\Entity\CustomerUser;
use Oro\Bundle\ProductBundle\Entity\Product;
use Symfony\Contracts\Translation\TranslatorInterface;
use Synolia\Bundle\FavoriteBundle\Entity\Favorite;

class FavoriteButtonAjaxHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TranslatorInterface $translator
    ) {
    }

    public function update(Product $product, ?CustomerUser $user): array
    {
        if (!$user instanceof CustomerUser) {
            return [
                'success' => false,
                'status' => 'warning',
                'message' => $this->translator->trans('synolia.favorite.frontend.messages.not_user'),
            ];
        }

        $favorite = $this->entityManager->getRepository(Favorite::class)->findOneBy([
            'customerUser' => $user,
            'organization' => $user->getOrganization(),
            'product' => $product,
        ]);

        return ($favorite instanceof Favorite)
            ? $this->remove($favorite)
            : $this->create($product, $user);
    }

    private function remove(mixed $favorite): array
    {
        $this->entityManager->remove($favorite);
        $this->entityManager->flush();

        return [
            'success' => true,
            'status' => 'removed',
            'message' => $this->translator->trans('synolia.favorite.frontend.messages.favorite_off'),
        ];
    }

    private function create(Product $product, CustomerUser $user): array
    {
        $favorite = new Favorite();
        $favorite->setProduct($product)
            ->setCustomerUser($user)
            ->setOrganization($user->getOrganization());

        $this->entityManager->persist($favorite);
        $this->entityManager->flush();

        return [
            'success' => true,
            'status' => 'created',
            'message' => $this->translator->trans('synolia.favorite.frontend.messages.favorite_on'),
        ];
    }
}
