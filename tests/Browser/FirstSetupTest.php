<?php

use Database\Seeders\OptionSeeder;
use Laravel\Dusk\Browser;

use function Pest\Faker\fake;

it('shows first run setup', function () {
    $this->seed(OptionSeeder::class);

    $this->browse(function (Browser $browser) {
        $browser->visit('/')
            ->assertSee('Ρύθμιση διαχειριστή συστήματος');
    });
});

it('completes first run setup', function () {
    $this->seed(OptionSeeder::class);

    $this->browse(function (Browser $browser) {
        $password = fake()->password(8);
        $browser->visit('/')
            ->assertSee('Ρύθμιση διαχειριστή συστήματος')
            ->type('name', fake()->name())
            ->type('email', fake()->email())
            ->type('username', fake()->username(6))
            ->type('password', $password)
            ->type('password_confirmation', $password)
            ->click('button[type="submit"]')
            ->waitForLocation('/home')
            ->assertPathIs('/home');
    });
});
