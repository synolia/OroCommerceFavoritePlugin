<?php

declare(strict_types=1);

namespace Synolia\Bundle\FavoriteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\CustomerBundle\Entity\Ownership\FrontendCustomerUserAwareTrait;
use Oro\Bundle\EntityBundle\EntityProperty\DatesAwareInterface;
use Oro\Bundle\EntityBundle\EntityProperty\DatesAwareTrait;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\OrganizationBundle\Entity\OrganizationAwareInterface;
use Oro\Bundle\OrganizationBundle\Entity\Ownership\OrganizationAwareTrait;
use Oro\Bundle\ProductBundle\Entity\Product;
use Synolia\Bundle\FavoriteBundle\Model\ExtendFavorite;

/**
 * @ORM\Entity(repositoryClass="Synolia\Bundle\FavoriteBundle\Entity\Repository\FavoriteRepository")
 * @ORM\Table(name="sy_favorite")

 * @Config(
 *      defaultValues={
 *          "ownership"={
 *              "organization_field_name"="organization",
 *              "organization_column_name"="organization_id",
 *              "frontend_owner_type"="FRONTEND_CUSTOMER",
 *              "frontend_owner_field_name"="customerUser",
 *              "frontend_owner_column_name"="customer_user_id",
 *          },
 *          "security"={
 *              "type"="ACL",
 *              "group_name"="commerce"
 *          },
 *      }
 * )
 * @ORM\HasLifecycleCallbacks
 */
class Favorite extends ExtendFavorite implements OrganizationAwareInterface, DatesAwareInterface
{
    use OrganizationAwareTrait;
    use DatesAwareTrait;
    use FrontendCustomerUserAwareTrait;

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\ProductBundle\Entity\Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $product;


    /**
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     * @return Favorite
     */
    public function setProduct(Product $product): self
    {
        $this->product = $product;
        return $this;
    }
}
