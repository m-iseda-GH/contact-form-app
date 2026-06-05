<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * トップページが正常に表示されることを確認する
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        // テスト実行時にViteのmanifestエラーが出ないようにする
        $this->withoutVite();

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
