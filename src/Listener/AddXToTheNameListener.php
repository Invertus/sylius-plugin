<?php

declare(strict_types=1);

namespace Acme\SyliusExamplePlugin\Listener;

use Acme\SyliusExamplePlugin\Entity\PromotionCouponInterface;
use Doctrine\Persistence\ObjectManager;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;

final class AddXToTheNameListener
{
    /**
     * @var ObjectManager
     */
    private $customerManager;

    public function __construct(ObjectManager $customerManager)
    {
        $this->customerManager = $customerManager;
    }

    public function __invoke(OrderInterface $order): void
    {
        /** @var PromotionCouponInterface|null $promotionCoupon */
        $promotionCoupon = $order->getPromotionCoupon();

        if ($promotionCoupon === null) {
            return;
        }

        /** @var CustomerInterface|null $referralSender */
        $referralSender = $promotionCoupon->getReferralSender();

        if ($referralSender === null) {
            return;
        }

        $referralSender->setFirstName($referralSender->getFirstName() . 'X');

        $this->customerManager->flush();
    }
}
