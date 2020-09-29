<?php

namespace Tests\Unit\Business\Entities;

use App\Entities\Customer\Customer;
use App\Entities\Freelancer\Freelancer;
use App\Entities\Job\Job;
use App\Entities\Proposal\Proposal;
use App\Events\Business\Freelancer\FreelancerRegistered;
use App\Events\Business\Proposal\ProposalPosted;
use App\Exceptions\FreelancerAlreadyAppliedForThisJobException;
use App\ValueObjects\CoverLetter;
use App\ValueObjects\Email;
use App\ValueObjects\EstimatedTime;
use App\ValueObjects\JobDescription;
use App\ValueObjects\JobTitle;
use App\ValueObjects\Money;
use App\ValueObjects\Password;
use Tests\TestCase;
use Tests\Unit\Traits\AssertContainsInstanceOf;

class FreelancerTest extends TestCase
{
    use AssertContainsInstanceOf;

    public function testRegister(): void
    {
        $email = Email::from('su_admin@admin.com');
        $password = Password::fromRaw('my_password');
        $hourRate = Money::usd(12.23);
        $freelancer = Freelancer::register($email, $password, $hourRate);

        self::assertContainsInstanceOf(FreelancerRegistered::class, $freelancer->releaseEvents());
        self::assertEmpty($freelancer->releaseEvents());

        self::assertSame($email, $freelancer->getEmail());
        self::assertSame($password, $freelancer->getPassword());
        self::assertSame($hourRate, $freelancer->getHourRate());
    }

    public function testApplyForJob(): void
    {
        $job = $this->makeJob();

        $email = Email::from('su_admi23n@admin.com');
        $password = Password::fromRaw('my_23password');
        $hourRate = Money::usd(22.23);

        $freelancer = Freelancer::register($email, $password, $hourRate);
        $coverLetter = CoverLetter::create('I can do this.... And also that...');
        $estimatedTime = EstimatedTime::create(10, 5, 20);

        $proposal = $freelancer->applyForJob($job, $estimatedTime, $coverLetter);

        self::assertSame($coverLetter, $proposal->getCoverLetter());
        self::assertSame($estimatedTime, $proposal->getEstimatedTime());
        self::assertSame($job, $proposal->getJob());
        self::assertSame($freelancer, $proposal->getFreelancer());

        self::assertContainsInstanceOf(ProposalPosted::class, $proposal->releaseEvents());
        self::assertEmpty($proposal->releaseEvents());
    }

    public function testPostProposalSecondTimeForSameJobWillThrowException(): void
    {
        $this->expectException(FreelancerAlreadyAppliedForThisJobException::class);

        $job = $this->makeJob();

        $email = Email::from('su_admi23n@admin.com');
        $password = Password::fromRaw('my_23password');
        $hourRate = Money::usd(22.23);

        $freelancer = Freelancer::register($email, $password, $hourRate);

        $freelancer->applyForJob(
            $job,
            EstimatedTime::days(5),
            CoverLetter::create('I can do this....')
        );

        Proposal::post(
            CoverLetter::create('I can do this with second way'),
            EstimatedTime::minutes(1),
            $job,
            $freelancer
        );
    }


    private function makeJob(): Job
    {
        $jobTitle = JobTitle::create('title');
        $jobDescription = JobDescription::create('some description');
        $customer = Customer::register(
            Email::from('someone@ukr.net'),
            Password::fromRaw('1234jjf')
        );

        return $customer->publishJob($jobTitle, $jobDescription);
    }
}
