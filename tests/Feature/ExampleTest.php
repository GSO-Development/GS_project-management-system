<?php

test('it redirects root route to login page', function () {
    $response = $this->get('/');

    $response->assertRedirect('/login');
});
