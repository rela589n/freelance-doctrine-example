<?php


namespace Tests\Unit\Business\UseCases;


use App\Entities\Customer\CustomersRepository;
use App\Events\Business\Customer\CustomerRegistered;
use App\UseCases\Customer\Register\RegisterCustomerCommand;
use App\UseCases\Customer\Register\RegisterCustomerDto;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Testing\Fakes\EventFake;
use Tests\TestCase;

class RegisterCustomerTest extends TestCase
{
    public function testSuccessfulRegister(): void
    {
        $dispatcher = new EventFake($this->createMock(Dispatcher::class));
        $entityManger = $this->createMock(EntityManagerInterface::class);
        $entityManger->expects(self::atLeastOnce())->method('persist');
        $repository = $this->createMock(CustomersRepository::class);
        $repository->method('existsCustomerWithEmail')->willReturn(false);

        $command = new RegisterCustomerCommand($dispatcher, $entityManger, $repository);
        $dto = new RegisterCustomerDto('my_email@gmail.com', 'password123');
        $command->execute($dto);

        $dispatcher->assertDispatched(CustomerRegistered::class);
    }
}
