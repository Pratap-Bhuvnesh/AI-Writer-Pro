<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     */
    /* public function test_basic_example(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Laravel');
        });
    } */
    public function test_login()
    {
        $this->browse(function ($browser) {
            $browser->visit('/login')
                    ->type('email', 'test@gmail.com')
                    ->type('password', '123456')
                    ->press('Login')
                    ->assertPathIs('/dashboard');
        });
    }
}
