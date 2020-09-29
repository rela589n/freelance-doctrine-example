<?php

namespace Tests\Unit\ValueObjects;

use App\ValueObjects\EstimatedTime;
use PHPUnit\Framework\TestCase;

class EstimatedTimeTest extends TestCase
{
    public function testCreateInMinutes(): void
    {
        $estimatedTime = EstimatedTime::minutes(20);

        self::assertSame(20 * 60, $estimatedTime->inSeconds());
    }

    public function testCreateInHours(): void
    {
        $estimatedTime = EstimatedTime::hours(10);

        self::assertSame(10 * 60 * 60, $estimatedTime->inSeconds());
    }

    public function testCreateInDays(): void
    {
        $estimatedTime = EstimatedTime::days(3);

        self::assertSame(3 * 24 * 60 * 60, $estimatedTime->inSeconds());
    }

    public function testTimeEqual(): void
    {
        $estimatedTime1 = EstimatedTime::days(2);
        $estimatedTime2 = EstimatedTime::hours(48);

        self::assertTrue($estimatedTime1->equals($estimatedTime2));

        $estimatedTime3 = EstimatedTime::minutes(1440);
        $estimatedTime4 = EstimatedTime::days(1);

        self::assertTrue($estimatedTime3->equals($estimatedTime4));
    }

    public function testTimeIsNotEqual(): void
    {
        $estimatedTime1 = EstimatedTime::hours(1);
        $estimatedTime2 = EstimatedTime::minutes(59);

        self::assertFalse($estimatedTime1->equals($estimatedTime2));

        $estimatedTime3 = EstimatedTime::days(5);
        $estimatedTime4 = EstimatedTime::days(6);
        self::assertFalse($estimatedTime3->equals($estimatedTime4));
    }

    public function testCreateCombined(): void
    {
        $estimatedTime = EstimatedTime::create(4, 10, 15);

        self::assertSame(4 * 24 * 60 * 60 + 10 * 60 * 60 + 15 * 60, $estimatedTime->inSeconds());
    }

    public function testPlusDays(): void
    {
        $estimatedTime1 = EstimatedTime::days(3);
        $estimatedTime2 = EstimatedTime::days(2);

        self::assertTrue(EstimatedTime::days(5)->equals($estimatedTime1->plus($estimatedTime2)));
    }

    public function testPlusHours(): void
    {
        $estimatedTime1 = EstimatedTime::hours(20);
        $estimatedTime2 = EstimatedTime::hours(4);

        self::assertTrue(EstimatedTime::hours(24)->equals($estimatedTime1->plus($estimatedTime2)));
    }

    public function testPlusMinutes(): void
    {
        $estimatedTime1 = EstimatedTime::minutes(20);
        $estimatedTime2 = EstimatedTime::minutes(40);

        self::assertTrue(EstimatedTime::hours(1)->equals($estimatedTime1->plus($estimatedTime2)));
    }
}
