<?php

namespace Tests\Unit\ValueObjects;

use App\Exceptions\JobTitleTooLongException;
use App\Exceptions\JobTitleTooShortException;
use App\ValueObjects\JobTitle;
use Illuminate\Support\Str;
use Tests\TestCase;

class JobTitleTest extends TestCase
{
    public function testCreate(): void
    {
        $jobDescription1 = JobTitle::create('some description');
        self::assertSame('some description', (string)$jobDescription1);

        $jobDescription2 = JobTitle::create('another description');
        self::assertSame('another description', (string)$jobDescription2);
    }

    public function testMustThrowExceptionIfDescriptionIsTooSort(): void
    {
        $this->expectException(JobTitleTooShortException::class);
        $jobDescription = JobTitle::create(Str::random(JobTitle::MIN_LENGTH - 1));
    }

    public function testMustThrowExceptionIfDescriptionIsTooLong(): void
    {
        $this->expectException(JobTitleTooLongException::class);
        $jobDescription = JobTitle::create(Str::random(JobTitle::MAX_LENGTH + 1));
    }
}
