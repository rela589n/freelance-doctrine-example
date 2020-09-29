<?php


namespace Tests\Unit\Business\UseCases;


use App\Events\Business\Freelancer\FreelancerLoggedIn;
use App\Events\Business\Freelancer\FreelancerLoggedOut;
use App\Exceptions\Authentication\FreelancerAuthenticationFailed;
use App\UseCases\Freelancer\Login\AuthenticateFreelancerCommand;
use App\UseCases\Freelancer\Login\LoginFreelancerDto;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Testing\Fakes\EventFake;
use Tests\TestCase;


class AuthenticateFreelancerTest extends TestCase
{
    private const USER_EMAIL = 'email@gmail.com';
    private const USER_PASSWORD = 'my_password';

    private Dispatcher $dispatcher;
    private StatefulGuard $guard;
    private AuthenticateFreelancerCommand $command;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dispatcher = new EventFake($this->createMock(Dispatcher::class));
        $this->guard = $this->createMock(StatefulGuard::class);

        $this->guard->method('attempt')
            ->willReturnCallback(
                function ($credentials) {
                    return $credentials['email'] == self::USER_EMAIL && $credentials['password'] === self::USER_PASSWORD;
                }
            );

        $this->guard->method('id')
            ->willReturn('uuidExample');

        $this->command = new AuthenticateFreelancerCommand($this->dispatcher, $this->guard);
    }

    public function testSuccessfulLogin(): void
    {
        $dto = new LoginFreelancerDto(self::USER_EMAIL, self::USER_PASSWORD, true);
        $this->command->login($dto);

        $this->dispatcher->assertDispatched(FreelancerLoggedIn::class);
    }

    public function testWillThrowExceptionIfEmailDontMatch(): void
    {
        $this->expectException(FreelancerAuthenticationFailed::class);

        $dto = new LoginFreelancerDto('my_world'.self::USER_EMAIL, self::USER_PASSWORD, true);
        $this->command->login($dto);
    }

    public function testWillThrowExceptionIfPasswordDontMatch(): void
    {
        $this->expectException(FreelancerAuthenticationFailed::class);

        $dto = new LoginFreelancerDto(self::USER_EMAIL, self::USER_PASSWORD . 'assd', true);
        $this->command->login($dto);
    }

    public function testSuccessfulLogout(): void
    {
        $this->guard->expects(self::once())->method('logout');

        $this->command->logout();

        $this->dispatcher->assertDispatched(FreelancerLoggedOut::class);
    }
}
