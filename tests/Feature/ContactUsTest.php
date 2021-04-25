<?php

namespace Tests\Feature;

use App\Models\ContactUs;
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

    public function testStoreSuccess()
    {
        $contact_us = factory(ContactUs::class)->create();
        $response = $this->call('POST', '/contact-us', [
            'name' => $contact_us->name,
            'email' => $contact_us->email,
            'phone' => $contact_us->phone,
            'subject' => $contact_us->subject,
            'message' => $contact_us->message,
        ]);
        $this->assertEquals(302, $response->status());
    }

    public function testStoreFaild()
    {
        $contact_us = factory(ContactUs::class)->create();
        $response = $this->call('POST', '/contact-us', [
            'subject' => $contact_us->subject,
            'message' => $contact_us->message,
        ]);
        $this->assertEquals(422, $response->status());
    }
}
