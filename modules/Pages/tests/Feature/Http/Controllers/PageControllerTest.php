<?php
namespace Modules\Pages\Tests\Feature\Http\Controllers;


test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
