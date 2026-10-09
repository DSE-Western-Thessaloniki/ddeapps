<?php

it('embeds the translations payload in the layout', function (): void {
    $this->withoutVite();

    $response = $this->get('/login');

    $response->assertOk();

    expect($response->getContent())
        ->toContain('window._translations')
        ->toContain('"el":');
});
