<?php

use App\Entities\Customer\Customer;
use App\Entities\Freelancer\Freelancer;
use App\ValueObjects\Email;
use App\ValueObjects\Money;
use App\ValueObjects\Password;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = app()->get(\Doctrine\ORM\EntityManagerInterface::class);

        $customers = [
            [
                'customer1@test.com',
                'customer1@test.com',
            ],
            [
                'customer2@test.com',
                'customer2@test.com',
            ],
        ];

        foreach ($customers as [$email, $pass]) {
            $customer = Customer::register(
                Email::from($email),
                Password::fromRaw($pass),
            );

            $entityManager->persist($customer);
        }

        $entityManager->flush();

        $freelancers = [
            [
                'freelancer1@test.com',
                'freelancer1@test.com',
                10,
            ],
            [
                'freelancer2@test.com',
                'freelancer2@test.com',
                20,
            ],
        ];

        foreach ($freelancers as [$email, $pass, $rate]) {
            $freelancer = Freelancer::register(
                Email::from($email),
                Password::fromRaw($pass),
                Money::usd($rate),
            );

            $entityManager->persist($freelancer);
        }

        $entityManager->flush();
    }
}
