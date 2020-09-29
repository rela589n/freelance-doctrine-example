<?php

namespace Tests\Unit\ValueObjects;

use App\ValueObjects\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    public function testCreateUsdFromInteger(): void
    {
        $money = Money::usd(10);

        self::assertSame(10.0, $money->inUsd());
    }

    public function testCreateUsdFromFloat(): void
    {
        $money = Money::usd(123.45);

        self::assertSame(123.45, $money->inUsd());
    }

    public function testMoneyEquals(): void
    {
        $money1 = Money::usd(123.1);
        $money2 = Money::usd(123.1);

        self::assertTrue($money1->equalsTo($money2));
    }

    public function test0Point1EqualsTo0Point1(): void
    {
        $money1 = Money::usd(.1);
        $money2 = Money::usd(.1);

        self::assertTrue($money1->equalsTo($money2));
    }

    public function test0Point3DoesntEqual0Point1(): void
    {
        $money1 = Money::usd(.1);
        $money2 = Money::usd(.3);

        self::assertFalse($money1->equalsTo($money2));
    }

    public function testAddIntValues(): void
    {
        $cash1 = Money::usd(12);
        $cash2 = Money::usd(23);

        self::assertTrue(
            Money::usd(35)
                ->equalsTo($cash1->add($cash2))
        );
    }

    public function testAddFloatValues(): void
    {
        $money1 = Money::usd(.1);
        $money2 = Money::usd(.2);

        self::assertTrue(
            Money::usd(.3)
                ->equalsTo($money1->add($money2))
        );
    }
}
