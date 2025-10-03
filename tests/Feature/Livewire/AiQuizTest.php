<?php

use Livewire\Volt\Volt;

it('can render', function () {
    $component = Volt::test('ai-quiz');

    $component->assertSee('');
});
