<?php

declare(strict_types=1);

namespace Acme\SyliusExamplePlugin\Controller;

use Acme\SyliusExamplePlugin\Entity\PromotionCouponInterface;
use Sylius\Component\Core\Model\PromotionInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\Core\Repository\PromotionRepositoryInterface;
use Sylius\Component\Promotion\Factory\PromotionCouponFactoryInterface;
use Sylius\Component\Promotion\Repository\PromotionCouponRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

final class ReferralPromotionCouponController extends AbstractController
{
    /**
     * @var PromotionRepositoryInterface
     */
    private $promotionRepository;

    /**
     * @var PromotionCouponFactoryInterface
     */
    private $promotionCouponFactory;

    /**
     * @var PromotionCouponRepositoryInterface
     */
    private $promotionCouponRepository;

    public function __construct(
        PromotionRepositoryInterface $promotionRepository,
        PromotionCouponFactoryInterface $promotionCouponFactory,
        PromotionCouponRepositoryInterface $promotionCouponRepository
    ) {
        $this->promotionRepository = $promotionRepository;
        $this->promotionCouponFactory = $promotionCouponFactory;
        $this->promotionCouponRepository = $promotionCouponRepository;
    }

    public function generateAction(): Response
    {
        $user = $this->getUser();

        /** @var ShopUserInterface $user */
        Assert::isInstanceOf($user, ShopUserInterface::class);

        /** @var PromotionInterface|null $promotion */
        $promotion = $this->promotionRepository->findOneBy(['code' => 'REFERRAL']);

        Assert::notNull($promotion);

        /** @var PromotionCouponInterface $promotionCoupon */
        $promotionCoupon = $this->promotionCouponFactory->createForPromotion($promotion);
        $promotionCoupon->setCode(md5(uniqid('', true)));
        $promotionCoupon->setReferralSender($user->getCustomer());

        $this->promotionCouponRepository->add($promotionCoupon);

        $this->addFlash('success', 'Generated coupon: ' . $promotionCoupon->getCode());

        return $this->redirectToRoute('sylius_shop_homepage');
    }
}
