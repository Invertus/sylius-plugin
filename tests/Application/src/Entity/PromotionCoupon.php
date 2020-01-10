<?php

declare(strict_types=1);

namespace Tests\Acme\SyliusExamplePlugin\Application\Entity;

use Acme\SyliusExamplePlugin\Entity\PromotionCouponTrait;
use Acme\SyliusExamplePlugin\Entity\PromotionCouponInterface;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\PromotionCoupon as BasePromotionCoupon;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_promotion_coupon")
 */
class PromotionCoupon extends BasePromotionCoupon implements PromotionCouponInterface
{
    use PromotionCouponTrait;
}
