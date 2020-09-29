<?php

namespace Tests\Unit\Business\UseCases;

use App\Events\Business\Freelancer\FreelancerRegistered;
use App\UseCases\Freelancer\Register\RegisterFreelancerCommand;
use App\UseCases\Freelancer\Register\RegisterFreelancerDto;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Testing\Fakes\EventFake;
use Tests\TestCase;

class RegisterFreelancerTest extends TestCase
{
    public function testSuccessfulRegister(): void
    {
        $dispatcher = new EventFake($this->createMock(Dispatcher::class));
        $entityManger = $this->createMock(EntityManagerInterface::class);
        $entityManger->expects(self::atLeastOnce())->method('persist');

        $command = new RegisterFreelancerCommand($dispatcher, $entityManger);
        $dto = new RegisterFreelancerDto('my_email@gmail.com', 'password123', 24.35);
        $command->execute($dto);

        $dispatcher->assertDispatched(FreelancerRegistered::class);
    }
}
