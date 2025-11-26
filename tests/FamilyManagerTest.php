<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\FamilyManager;

class FamilyManagerTest extends TestCase
{
    public function testAddTeenAndGetWallet()
    {
        $fm = new FamilyManager();
        $fm->addTeen('alice', 5.0);
        $wallet = $fm->getWallet('alice');
        $this->assertEquals(0.0, $wallet->getBalance());
    }

    public function testDepositAndSpend()
    {
        $fm = new FamilyManager();
        $fm->addTeen('bob', 10.0);
        $fm->depositToTeen('bob', 20.0);
        $this->assertEquals(20.0, $fm->getWallet('bob')->getBalance());
        $fm->spendFromTeen('bob', 5.0);
        $this->assertEquals(15.0, $fm->getWallet('bob')->getBalance());
    }

    public function testWeeklyAllowance()
    {
        $fm = new FamilyManager();
        $fm->addTeen('carol', 7.0);
        $fm->processAllWeeklyAllowances();
        $this->assertEquals(7.0, $fm->getWallet('carol')->getBalance());
        $fm->processAllWeeklyAllowances();
        $this->assertEquals(14.0, $fm->getWallet('carol')->getBalance());
    }

    public function testMultipleTeens()
    {
        $fm = new FamilyManager();
        $fm->addTeen('dave', 2.0);
        $fm->addTeen('eve', 3.0);
        $fm->depositToTeen('dave', 5.0);
        $fm->depositToTeen('eve', 10.0);
        $fm->processAllWeeklyAllowances();
        $this->assertEquals(7.0, $fm->getWallet('dave')->getBalance());
        $this->assertEquals(13.0, $fm->getWallet('eve')->getBalance());
    }

    public function testAddTeenTwiceThrows()
    {
        $this->expectException(\Exception::class);
        $fm = new FamilyManager();
        $fm->addTeen('frank', 1.0);
        $fm->addTeen('frank', 2.0);
    }

    public function testUnknownTeenThrows()
    {
        $this->expectException(\Exception::class);
        $fm = new FamilyManager();
        $fm->getWallet('ghost');
    }
}
