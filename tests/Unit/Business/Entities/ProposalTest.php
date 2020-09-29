<?php

namespace Tests\Unit\Business\Entities;

use App\Entities\Customer\Customer;
use App\Entities\Freelancer\Freelancer;
use App\Entities\Job\Job;
use App\Entities\Proposal\Proposal;
use App\Events\Business\Proposal\ProposalPosted;
use App\ValueObjects\CoverLetter;
use App\ValueObjects\Email;
use App\ValueObjects\EstimatedTime;
use App\ValueObjects\JobDescription;
use App\ValueObjects\JobTitle;
use App\ValueObjects\Money;
use App\ValueObjects\Password;
use Tests\TestCase;
use Tests\Unit\Traits\AssertContainsInstanceOf;

class ProposalTest extends TestCase
{
    use AssertContainsInstanceOf;

    public function testPostProposal(): void
    {
        $customer = $this->makeCustomer();
        $job = $this->makeJob($customer);

        $freelancer = $this->makeFreelancer();
        $coverLetter = CoverLetter::create('I can do this....');
        $estimatedTime = EstimatedTime::days(5);

        $proposal = Proposal::post($coverLetter, $estimatedTime, $job, $freelancer);
        self::assertContains($proposal, $job->getProposals());
        self::assertContains($proposal, $freelancer->getProposals());
        self::assertContainsInstanceOf(ProposalPosted::class, $proposal->releaseEvents());
        self::assertEmpty($proposal->releaseEvents());

        self::assertSame($coverLetter, $proposal->getCoverLetter());
        self::assertSame($estimatedTime, $proposal->getEstimatedTime());
        self::assertSame($job, $proposal->getJob());
        self::assertSame($freelancer, $proposal->getFreelancer());
    }

    private function makeCustomer(): Customer
    {
        return Customer::register(
            Email::from('someone@ukr.net'),
            Password::fromRaw('1234jjf')
        );
    }

    private function makeJob(Customer $customer): Job
    {
        $jobTitle = JobTitle::create('title');
        $jobDescription = JobDescription::create('some description');
        return $customer->publishJob($jobTitle, $jobDescription);
    }

    private function makeFreelancer(): Freelancer
    {
        $email = Email::from('su_admi23n@admin.com');
        $password = Password::fromRaw('my_23password');
        $hourRate = Money::usd(22.23);

        return Freelancer::register($email, $password, $hourRate);
    }
}
