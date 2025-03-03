<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class GlobalSearchTest extends DuskTestCase
{
    /** @test */
    public function it_can_globally_search()
    {
        $this->browse(function (Browser $browser) {
            User::first()->forceFill([
                'name'  => 'Faruk Nasir',
                'email' => 'pascal@protone.media',
            ])->save();

            $users = User::query()
                ->select(['id', 'name', 'email'])
                ->orderBy('name')
                ->get();

            $browser->visit('/users/eloquent')
                ->waitFor('table')
                // First user
                ->assertSeeIn('tr:first-child td:nth-child(1)', $users->get(0)->name)
                ->assertDontSee('Faruk Nasir')
                ->type('global', 'Faruk Nasir')
                ->waitForText('pascal@protone.media')
                ->type('global', ' ')
                ->waitUntilMissingText('pascal@protone.media')
                ->type('global', 'pascal@protone.media')
                ->waitForText('Faruk Nasir');
        });
    }
}
