<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ContactUsTest extends TestCase
{
    public function testIndexSuccess()
    {
        $response = $this->call('GET', '/contact-us');
        $this->assertEquals(200, $response->status());
    }

    // public function testStoreSuccess()
    // {
    //     $response = $this->call('POST', '/contact-us', [
    //         'name' => 'test',
    //         'email' => 'test@test.com',
    //         'phone' => '1111111111',
    //         'subject' => 'test',
    //         'message' => 'test',
    //     ]);
    //     $this->assertEquals(302, $response->status());
    // }

    public function testStoreFaild()
    {
        $response = $this->call('POST', '/contact-us', [
            'name' => 'test',
            'email' => 'test@test.com',
            'subject' => 'test',
            'message' => 'test',
        ]);
        $this->assertEquals(422, $response->status());
    }
}
