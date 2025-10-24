<?php

use App\Livewire\ReportQuestion;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(ReportQuestion::class)
        ->assertStatus(200);
});
