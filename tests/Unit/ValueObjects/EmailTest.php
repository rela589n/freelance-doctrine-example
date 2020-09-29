<?php

namespace Tests\Unit\ValueObjects;

use App\Exceptions\EmailInvalidException;
use App\ValueObjects\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    public function testCreateValidEmail(): void
    {
        $validCases = [
            'hello-world@gmail.com',
            'admin123@mail.ru',
        ];

        foreach ($validCases as $strEmail) {
            $email = Email::from($strEmail);
            self::assertSame($strEmail, (string)$email);
        }
    }

    public function testThrowsInvalidArgumentExceptionWhenEmailIsInvalid(): void
    {
        $this->expectException(EmailInvalidException::class);
        $email = Email::from('not valid');
    }
}
