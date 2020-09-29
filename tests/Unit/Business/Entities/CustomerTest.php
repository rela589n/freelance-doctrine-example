<?php


namespace Tests\Unit\Business\Entities;

use App\Entities\Customer\Customer;
use App\Events\Business\Customer\CustomerRegistered;
use App\ValueObjects\Email;
use App\ValueObjects\Password;
use Tests\TestCase;
use Tests\Unit\Traits\AssertContainsInstanceOf;

class CustomerTest extends TestCase
{
    use AssertContainsInstanceOf;

    public function testRegisterEventIsDispatched(): void
    {
        $email = Email::from('admin@admin.com');
        $password = Password::fromRaw('123456');
        $customer = Customer::register($email, $password);
        self::assertContainsInstanceOf(CustomerRegistered::class, $customer->releaseEvents());
        self::assertEmpty($customer->releaseEvents());

        self::assertSame($email, $customer->getEmail());
        self::assertSame($password, $customer->getPassword());
    }
}
