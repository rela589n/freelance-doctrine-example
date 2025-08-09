<?php

declare(strict_types=1);

namespace Tests\Unit\Vendor;

use App\ValueObjects\Email;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Common\Collections\Expr\Value;
use JetBrains\PhpStorm\Immutable;
use PHPUnit\Framework\TestCase;
use stdClass;
use Tests\Unit\Vendor\Stub\EncapsulatedObject;

#[Immutable]
final class DoctrineCollectionsTest extends TestCase
{
    public function testSimpleCriteriaCollections(): void
    {
        $collection = new ArrayCollection(
            [
                [
                    'name' => 'john'
                ],
                [
                    'name' => 'doe',
                ]
            ]
        );

        $criteria = new Criteria();
        $criteria->where(new Comparison('name', '=', 'doe'));

        self::assertSame(['name' => 'doe'], $collection->matching($criteria)->first());
    }

    public function testCriteriaWithSimpleObjects(): void
    {
        $stdClass = new stdClass();
        $stdClass->name = 'john';
        $collection = new ArrayCollection([$stdClass]);

        $criteria = new Criteria();
        $criteria->where(new Comparison('name', '=', 'john'));

        self::assertSame('john', $collection->matching($criteria)->first()->name);
    }

    public function testCriteriaWithComplexObjects(): void
    {
        $object = new EncapsulatedObject('John');
        $collection = new ArrayCollection([$object]);

        $criteria = new Criteria();
        $criteria->where(new Comparison('name', '=', 'John'));

        self::assertSame('John', $collection->matching($criteria)->first()->getName());
    }

    public function _testCriteriaWithMagicStringMethod(): void
    {
        $collection = new ArrayCollection([Email::from('test@test.com')]);
        $criteria = new Criteria();
        $criteria->where(new Comparison('', '=', 'test@test.com'));
        dd($collection->matching($criteria));
    }
}
