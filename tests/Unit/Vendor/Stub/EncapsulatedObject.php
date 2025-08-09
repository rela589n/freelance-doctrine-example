<?php

declare(strict_types=1);

namespace Tests\Unit\Vendor\Stub;

use JetBrains\PhpStorm\Immutable;

#[Immutable]
final class EncapsulatedObject
{
    private string $name;

    public function __construct(string $name) { $this->name = $name; }

    public function getName(): string
    {
        return $this->name;
    }
}
