<?php

namespace Tests\Unit\ValueObjects;

use App\Exceptions\CoverLetterTooLongException;
use App\Exceptions\CoverLetterTooShortException;
use App\ValueObjects\CoverLetter;
use Illuminate\Support\Str;
use Tests\TestCase;

class CoverLetterTest extends TestCase
{
    public function testCreate(): void
    {
        $coverLetter1 = CoverLetter::create('My cover letter');
        $coverLetter2 = CoverLetter::create('My another cover letter');

        self::assertSame('My cover letter', (string)$coverLetter1);
        self::assertSame('My another cover letter', (string)$coverLetter2);
    }

    public function testThrowsExceptionIfLetterIsTooShort(): void
    {
        $this->expectException(CoverLetterTooShortException::class);

        CoverLetter::create(Str::random(CoverLetter::MIN_LENGTH - 1));
    }

    public function testThrowsExceptionIfLetterIsTooLong(): void
    {
        $this->expectException(CoverLetterTooLongException::class);

        CoverLetter::create(Str::random(CoverLetter::MAX_LENGTH + 1));
    }
}
