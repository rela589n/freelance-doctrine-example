<?php


namespace Tests\Unit\Business\UseCases;


use App\Events\Business\Customer\CustomerLoggedIn;
use App\Events\Business\Customer\CustomerLoggedOut;
use App\Exceptions\Authentication\CustomerAuthenticationFailed;
use App\UseCases\Customer\Login\AuthenticateCustomerCommand;
use App\UseCases\Customer\Login\LoginCustomerDto;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Testing\Fakes\EventFake;
use Tests\TestCase;


class AuthenticateCustomerTest extends TestCase
{
    private const USER_EMAIL = 'email@gmail.com';
    private const USER_PASSWORD = 'my_password';

    private Dispatcher $dispatcher;
    private StatefulGuard $guard;
    private AuthenticateCustomerCommand $command;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dispatcher = new EventFake($this->createMock(Dispatcher::class));
        $this->guard = $this->createMock(StatefulGuard::class);

        $this->guard->method('attempt')
            ->willReturnCallback(
                function ($credentials) {
                    return $credentials['email.email'] == self::USER_EMAIL && $credentials['password'] === self::USER_PASSWORD;
                }
            );

        $this->guard->method('id')
            ->willReturn('uuidExample');

        $this->command = new AuthenticateCustomerCommand($this->dispatcher, $this->guard);
    }


    public function testSuccessfulLogin(): void
    {
        $dto = new LoginCustomerDto(self::USER_EMAIL, self::USER_PASSWORD, true);
        $this->command->login($dto);

        $this->dispatcher->assertDispatched(CustomerLoggedIn::class);
    }

    public function testWillThrowExceptionIfEmailDontMatch(): void
    {
        $this->expectException(CustomerAuthenticationFailed::class);

        $dto = new LoginCustomerDto('my_world'.self::USER_EMAIL, self::USER_PASSWORD, true);
        $this->command->login($dto);
    }

    public function testWillThrowExceptionIfPasswordDontMatch(): void
    {
        $this->expectException(CustomerAuthenticationFailed::class);

        $dto = new LoginCustomerDto(self::USER_EMAIL, self::USER_PASSWORD.'assd', true);
        $this->command->login($dto);
    }


    public function testSuccessfulLogout(): void
    {
        $this->guard->expects(self::once())->method('logout');

        $this->command->logout();

        $this->dispatcher->assertDispatched(CustomerLoggedOut::class);
    }
}
