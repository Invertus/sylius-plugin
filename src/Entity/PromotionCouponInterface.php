<?php

declare(strict_types=1);

namespace Acme\SyliusExamplePlugin\Entity;

use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\PromotionCouponInterface as BasePromotionCouponInterface;

interface PromotionCouponInterface extends BasePromotionCouponInterface
{
    public function getReferralSender(): ?CustomerInterface;

    public function setReferralSender(?CustomerInterface $referralSender): void;
}
