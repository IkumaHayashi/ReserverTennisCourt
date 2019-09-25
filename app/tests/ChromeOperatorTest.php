<?php

use PHPUnit\Framework\TestCase;
require_once(dirname(__FILE__).'/../model/ChromeOperator.php');

class ChromeOperatorTest extends TestCase
{
    /**
     * @test
     */
    public function コンストラクタのテスト(): void
    {
        $chromeOperator = new ChromeOperator('', 'https://www.google.co.jp/','');
        $chromeOperator = null;
    }
}