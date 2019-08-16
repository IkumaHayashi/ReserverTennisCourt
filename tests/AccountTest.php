<?php

use PHPUnit\Framework\TestCase;
require_once(dirname(__FILE__).'/../model/Account.php');

class AccountTest extends TestCase
{
    /**
     * @test
     */
    public function コンストラクタのテスト(): void
    {
        $account = new Account('test_id','test_pw');
        $this->assertEquals('test_id',$account->id);
        $this->assertEquals('test_pw',$account->password);
    }
}