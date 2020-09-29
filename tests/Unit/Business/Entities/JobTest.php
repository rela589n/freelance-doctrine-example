<?php

namespace Tests\Unit\Business\Entities;

use App\Entities\Customer\Customer;
use App\Entities\Job\Job;
use App\Events\Business\Job\JobPublished;
use App\ValueObjects\Email;
use App\ValueObjects\JobDescription;
use App\ValueObjects\JobTitle;
use App\ValueObjects\Password;
use Tests\TestCase;
use Tests\Unit\Traits\AssertContainsInstanceOf;

class JobTest extends TestCase
{
    use AssertContainsInstanceOf;

    public function testPublishJob(): void
    {
        $jobTitle = JobTitle::create('title');
        $jobDescription = JobDescription::create('some description');

        $customer = Customer::register(
            Email::from('someone@ukr.net'),
            Password::fromRaw('1234jjf')
        );

        $job = Job::publish($jobTitle, $jobDescription, $customer);

        self::assertSame($jobDescription, $job->getDescription());
        self::assertSame($jobTitle, $job->getTitle());
        self::assertSame($customer, $job->getCustomer());

        self::assertContainsInstanceOf(JobPublished::class, $job->releaseEvents());
        self::assertEmpty($job->releaseEvents());
    }

    public function testCustomerPublishJob(): void
    {
        $jobTitle = JobTitle::create('title');
        $jobDescription = JobDescription::create('some description');

        $customer = Customer::register(
            Email::from('someone@gmail.com'),
            Password::fromRaw('sword12')
        );

        $job = $customer->publishJob($jobTitle, $jobDescription);
        self::assertContainsInstanceOf(JobPublished::class, $job->releaseEvents());
        self::assertEmpty($job->releaseEvents());

        self::assertSame($jobDescription, $job->getDescription());
        self::assertSame($jobTitle, $job->getTitle());
        self::assertSame($customer, $job->getCustomer());
    }
}
