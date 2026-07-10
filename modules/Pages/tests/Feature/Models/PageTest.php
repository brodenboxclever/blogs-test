<?php
namespace Modules\Pages\Tests\Feature\Models;


test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
