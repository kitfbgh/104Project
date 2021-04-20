<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * 模擬使用者登入
     *
     * @return void
     */
    protected function demoUserLoginIn()
    {
        $user = factory(User::class)->create();
        // Use model in tests...
        // 登入 user
        $this->be($user);
    }

    /**
     * 模擬管理者登入
     *
     * @return void
     */
    protected function demoAdminLoginIn()
    {
        $admin = factory(User::class)->create();
        $admin->update(['role' => User::ROLE_ADMIN]);
        // Use model in tests...
        // 登入 admin
        $this->be($admin);
    }
}
