<?php

declare(strict_types=1);

namespace Synolia\Bundle\FavoriteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\CustomerBundle\Entity\Ownership\FrontendCustomerUserAwareTrait;
use Oro\Bundle\EntityBundle\EntityProperty\DatesAwareInterface;
use Oro\Bundle\EntityBundle\EntityProperty\DatesAwareTrait;
use Oro\Bundle\EntityConfigBundle\Metadata\Attribute\Config;
use Oro\Bundle\EntityExtendBundle\Entity\ExtendEntityInterface;
use Oro\Bundle\EntityExtendBundle\Entity\ExtendEntityTrait;
use Oro\Bundle\OrganizationBundle\Entity\OrganizationAwareInterface;
use Oro\Bundle\OrganizationBundle\Entity\Ownership\OrganizationAwareTrait;
use Oro\Bundle\ProductBundle\Entity\Product;

#[ORM\Entity(repositoryClass: 'Synolia\Bundle\FavoriteBundle\Entity\Repository\FavoriteRepository')]
#[ORM\Table(name: 'synolia_favorite')]
#[Config(defaultValues: [
    'ownership' => [
        'organization_field_name' => 'organization',
        'organization_column_name' => 'organization_id',
        'frontend_owner_type' => 'FRONTEND_CUSTOMER',
        'frontend_owner_field_name' => 'customerUser',
        'frontend_owner_column_name' => 'customer_user_id', ],
    'security' => [
        'type' => 'ACL',
        'group_name' => 'commerce'],
])]
class Favorite implements ExtendEntityInterface, OrganizationAwareInterface, DatesAwareInterface
{
    use DatesAwareTrait;
    use ExtendEntityTrait;
    use FrontendCustomerUserAwareTrait;
    use OrganizationAwareTrait;

    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    protected ?int $id;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    protected Product $product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
