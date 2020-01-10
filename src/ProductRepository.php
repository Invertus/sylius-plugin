<?php

declare(strict_types=1);

namespace Acme\SyliusExamplePlugin;

use Doctrine\ORM\QueryBuilder;
use Sylius\Component\Core\Model\ChannelInterface;

final class ProductRepository extends \Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductRepository
{
    public function createProductSearchQueryBuilder(
        ChannelInterface $channel,
        string $locale,
        array $sorting = []
    ): QueryBuilder {
        $queryBuilder = $this->createQueryBuilder('o')
            ->distinct()
            ->addSelect('translation')
            ->innerJoin('o.translations', 'translation', 'WITH', 'translation.locale = :locale')
            ->andWhere(':channel MEMBER OF o.channels')
            ->andWhere('o.enabled = true')
            ->setParameter('locale', $locale)
            ->setParameter('channel', $channel)
        ;

        // Grid hack, we do not need to join these if we don't sort by price
        if (isset($sorting['price'])) {
            // Another hack, the subquery to get the first position variant
            $subQuery = $this->createQueryBuilder('m')
                ->select('min(v.position)')
                ->innerJoin('m.variants', 'v')
                ->andWhere('m.id = :product_id')
            ;

            $queryBuilder
                ->addSelect('variant')
                ->addSelect('channelPricing')
                ->innerJoin('o.variants', 'variant')
                ->innerJoin('variant.channelPricings', 'channelPricing')
                ->andWhere('channelPricing.channelCode = :channelCode')
                ->andWhere(
                    $queryBuilder->expr()->in(
                        'variant.position',
                        str_replace(':product_id', 'o.id', $subQuery->getDQL())
                    )
                )
                ->setParameter('channelCode', $channel->getCode())
            ;
        }

        return $queryBuilder;
    }
}
