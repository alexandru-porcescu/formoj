<?php

namespace Code16\Formoj\Tests;

use Illuminate\Database\Eloquent\Factory;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Constraint\ArraySubset;
use PHPUnit\Util\InvalidArgumentHelper;

class FormojTestCase extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->make(Factory::class)
            ->load(__DIR__ . '/../database/factories');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [\Code16\Formoj\FormojServiceProvider::class];
    }

    /**
     * Asserts that an array has a specified subset.
     * This method was taken over from PHPUnit where it was deprecated. See links for more info.
     *
     * @param  array|\ArrayAccess  $subset
     * @param  array|\ArrayAccess  $array
     * @param  bool  $checkForObjectIdentity
     * @param  string  $message
     * @return void
     *
     * @link https://github.com/laravel/framework/blob/695a29928d5f3e595363306cf62ba4ff653d73ba/src/Illuminate/Foundation/Testing/Assert.php
     * @link https://github.com/sebastianbergmann/phpunit/issues/3494
     */
    public static function assertArraySubset($subset, $array, bool $checkForObjectIdentity = false, string $message = ''): void
    {
        if (! (is_array($subset) || $subset instanceof ArrayAccess)) {
            throw InvalidArgumentHelper::factory(1, 'array or ArrayAccess');
        }

        if (! (is_array($array) || $array instanceof ArrayAccess)) {
            throw InvalidArgumentHelper::factory(2, 'array or ArrayAccess');
        }

        $constraint = new ArraySubset($subset, $checkForObjectIdentity);

        static::assertThat($array, $constraint, $message);
    }
}