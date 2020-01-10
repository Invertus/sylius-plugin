<?php

declare(strict_types=1);

namespace Acme\SyliusExamplePlugin\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\CustomerInterface;

trait PromotionCouponTrait
{
    /**
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Customer\Model\CustomerInterface")
     * @ORM\JoinColumn(name="referral_sender", nullable=true)
     *
     * @var CustomerInterface|null
     */
    protected $referralSender;

    public function getReferralSender(): ?CustomerInterface
    {
        return $this->referralSender;
    }

    public function setReferralSender(?CustomerInterface $referralSender): void
    {
        $this->referralSender = $referralSender;
    }
}
