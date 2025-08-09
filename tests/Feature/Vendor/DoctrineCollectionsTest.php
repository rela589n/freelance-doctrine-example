<?php

declare(strict_types=1);

namespace Tests\Feature\Vendor;

use App\Entities\Customer\Customer;
use JetBrains\PhpStorm\Immutable;
use LaravelDoctrine\ORM\Facades\EntityManager;
use Tests\TestCase;

#[Immutable]
final class DoctrineCollectionsTest extends TestCase
{
    public function testQueryBuilder(): void
    {
        $qb = EntityManager::createQueryBuilder();

        $qb->select(['customer'])
            ->from(Customer::class, 'customer')
            ->where(new \Doctrine\ORM\Query\Expr\Comparison('customer.uuid', '=', ':uuid'))
            ->setParameter(':uuid', '9521ab25-16bc-4833-844e-4fe58e2da58e');
        dd($qb->getQuery()->getResult());
    }
}
