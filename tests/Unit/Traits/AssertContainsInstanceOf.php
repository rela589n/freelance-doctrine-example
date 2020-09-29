<?php


namespace Tests\Unit\Traits;


use PHPUnit\Framework\ExpectationFailedException;

trait AssertContainsInstanceOf
{
    public static function assertContainsInstanceOf(string $className, array $haystack): void
    {
        foreach ($haystack as $item) {
            if ($item instanceof $className) {
                return;
            }
        }

        $classNames = array_map(fn($o) => get_class($o), $haystack);
        $classNames = implode(',', $classNames);
        $classNames = "[$classNames]";

        throw new ExpectationFailedException("instance of \"$className\" class must be present in collection of: $classNames.");
    }
}
