<?php

namespace Tests\Unit\ValueObjects;

use App\Exceptions\PasswordTooLongException;
use App\Exceptions\PasswordTooShortException;
use App\ValueObjects\Password;
use Illuminate\Support\Str;
use Tests\TestCase;

class PasswordTest extends TestCase
{
    public function testCreatePasswordFromRawString(): void
    {
        $password = Password::fromRaw('my password');

        self::assertTrue($password->verify('my password'));
    }

    public function testCreatePasswordFromHash(): void
    {
        $hash = '$2y$10$.8UGKD5UeYCmBiX7t.9yZObMEHItVmp1PAxGLJgNXIrOaCo55HCL6'; // 'hello, password'

        $password = Password::fromHash($hash);
        self::assertTrue($password->verify('hello, password'));
    }

    public function test_toString_givesTheSameValueAs_hash(): void
    {
        $hash = '$2y$10$2wEOQO6roqniazPo1gCdVeAUxZ5G5qll4aIazUBI2nUP.hvaOcIKG';
        $password = Password::fromHash($hash);

        self::assertSame($hash, $password->hash());
        self::assertSame($password->hash(), (string)$password);
    }

    public function testThrowsExceptionIfPasswordTooShort(): void
    {
        $this->expectException(PasswordTooShortException::class);

        $password = Password::fromRaw(Str::random(Password::MIN_LENGTH - 1));
    }

    public function testThrowsExceptionIfPasswordIsTooLong(): void
    {
        $this->expectException(PasswordTooLongException::class);

        $password = Password::fromRaw(Str::random(Password::MAX_LENGTH + 1));
    }

    public function testExceptionIsNotThrownIfPasswordLengthIs_MIN_LENGTH(): void
    {
        $rawPassword = Str::random(Password::MIN_LENGTH);

        $password = Password::fromRaw($rawPassword);
        self::assertTrue($password->verify($rawPassword));
    }

    public function testExceptionIsNotThrownIfPasswordLengthIs_MAX_LENGTH(): void
    {
        $rawPassword = Str::random(Password::MAX_LENGTH);

        $password = Password::fromRaw($rawPassword);
        self::assertTrue($password->verify($rawPassword));
    }

    public function testIsCompatibleWith_password_verify(): void
    {
        $password = Password::fromRaw('password123');

        self::assertTrue(password_verify('password123', $password));
    }
}
