<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class HttpTest extends TestCase
{
    public function testGet()
    {
        $response = Http::get("https://enhmm1ik062ud.x.pipedream.net");
        self::assertTrue($response->ok());
    }

    public function testPost()
    {
        $response = Http::post("https://enhmm1ik062ud.x.pipedream.net");
        self::assertTrue($response->ok());
    }

    public function testDelete()
    {
        $response = Http::delete("https://enhmm1ik062ud.x.pipedream.net");
        self::assertTrue($response->ok());
    }

    public function testResponse()
    {
        $response = Http::get("https://enhmm1ik062ud.x.pipedream.net");
        self::assertEquals(200, $response->status());
        self::assertNotNull($response->headers());
        self::assertNotNull($response->body());

        $json = $response->json();
        self::assertTrue($json['success']);
    }

    public function testQueryParameter()
    {
        $response = Http::withQueryParameters([
            'page' => 1,
            'limit' => 10,
        ])->get("https://enhmm1ik062ud.x.pipedream.net");
        self::assertTrue($response->ok());
    }

    public function testHeader()
    {
        $response = Http::withQueryParameters([
            'page' => 1,
            'limit' => 10,
        ])->withHeaders([
            'Accept' => 'application/json',
            'X-Request-ID' => '123123123',
        ])->get("https://enhmm1ik062ud.x.pipedream.net");
        self::assertTrue($response->ok());
    }

    public function testCookie()
    {
        $response = Http::withQueryParameters([
            'page' => 1,
            'limit' => 10,
        ])->withHeaders([
            'Accept' => 'application/json',
            'X-Request-ID' => '123123123',
        ])->withCookies([
            "SessionId" => "3242432423",
            "UserId" => "eko",
        ], "enhmm1ik062ud.x.pipedream.net")->get("https://enhmm1ik062ud.x.pipedream.net");
        self::assertTrue($response->ok());
    }

    public function testFormPost()
    {
        $response = Http::asForm()->post("https://enhmm1ik062ud.x.pipedream.net", [
            "username" => "admin",
            "password" => "admin"
        ]);
        self::assertTrue($response->ok());
    }

    public function testMultipart()
    {
        $response = Http::asMultipart()
            ->attach("profile", file_get_contents(__DIR__ . '/HttpTest.php') , "profile.jpg")
            ->post("https://enhmm1ik062ud.x.pipedream.net", [
                "username" => "admin",
                "password" => "admin"
            ]);
        self::assertTrue($response->ok());
    }

    public function testJSON()
    {
        $response = Http::asJson()
            ->post("https://enhmm1ik062ud.x.pipedream.net", [
                "username" => "admin",
                "password" => "admin"
            ]);
        self::assertTrue($response->ok());
        $response->throw();
    }

    public function testTimeout()
    {
        $response = Http::timeout(1)->asJson()
            ->post("https://enhmm1ik062ud.x.pipedream.net", [
                "username" => "admin",
                "password" => "admin"
            ]);
        self::assertTrue($response->ok());
    }

    public function testRetry()
    {
        $response = Http::timeout(1)->retry(5, 1000)->asJson()
            ->post("https://enhmm1ik062ud.x.pipedream.net", [
                "username" => "admin",
                "password" => "admin"
            ]);
        self::assertTrue($response->ok());
    }

    public function testThrowError()
    {
        $this->assertThrows(function (){
            $response = Http::get("https://www.programmerzamannow.com/not-found");
            self::assertEquals(404, $response->status());
            $response->throw();
        }, RequestException::class);
    }


}
