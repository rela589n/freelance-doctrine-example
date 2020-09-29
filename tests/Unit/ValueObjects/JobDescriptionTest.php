<?php

namespace Tests\Unit\ValueObjects;

use App\Exceptions\JobDescriptionTooLongException;
use App\Exceptions\JobDescriptionTooShortException;
use App\ValueObjects\JobDescription;
use Illuminate\Support\Str;
use Tests\TestCase;

class JobDescriptionTest extends TestCase
{
    public function testCreate(): void
    {
        $jobDescription1 = JobDescription::create('some description');
        self::assertSame('some description', (string)$jobDescription1);

        $jobDescription2 = JobDescription::create('another description');
        self::assertSame('another description', (string)$jobDescription2);
    }

    public function testMustThrowExceptionIfDescriptionIsTooSort(): void
    {   //0969568707
        $this->expectException(JobDescriptionTooShortException::class);
        $jobDescription = JobDescription::create(Str::random(JobDescription::MIN_LENGTH - 1));
    }

    public function testMustThrowExceptionIfDescriptionIsTooLong(): void
    {
        $this->expectException(JobDescriptionTooLongException::class);
        $jobDescription = JobDescription::create(Str::random(JobDescription::MAX_LENGTH + 1));
    }
}
