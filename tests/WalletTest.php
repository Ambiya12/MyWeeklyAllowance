<?php

namespace MyWeeklyAllowance;

use PHPUnit\Framework\TestCase;
use App\Wallet;

class WalletTest extends TestCase
{
    public function testNewWalletHasZeroBalance(): void
    {
        $wallet = new Wallet();
        // On veut une méthode getBalance() qui retourne un float
        $this->assertSame(0.0, $wallet->getBalance());
    }

    public function testDepositIncreasesBalance(): void
    {
        $wallet = new Wallet();
        $wallet->deposit(50.0);

        $this->assertSame(50.0, $wallet->getBalance());
    }

    public function testCannotDepositNegativeAmount(): void
    {
        $wallet = new Wallet();
        $this->expectException(\InvalidArgumentException::class);
        $wallet->deposit(-10);
    }

    public function testSpendDecreasesBalance(): void
    {
        $wallet = new Wallet();
        $wallet->deposit(100.0); // On met de l'argent d'abord

        $wallet->spend(20.0, 'Test'); // L'ado achète un truc à 20 balles

        $this->assertSame(80.0, $wallet->getBalance());
    }

    public function testCannotSpendMoreThanBalance(): void
    {
        $wallet = new Wallet();
        $wallet->deposit(10.0);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Fonds insuffisants");

        $wallet->spend(50.0);
    }

    public function testProcessWeeklyAllowanceAddsMoney(): void
    {
        // On crée un wallet avec 10€ d'allocation hebdo
        $wallet = new Wallet(10.0);

        // On simule le passage de la semaine
        $wallet->processWeeklyAllowance();

        $this->assertSame(10.0, $wallet->getBalance());

        // Si on le refait une 2ème fois
        $wallet->processWeeklyAllowance();
        $this->assertSame(20.0, $wallet->getBalance());
    }

    public function testSpendingIsRecordedInHistory(): void
    {
        $wallet = new Wallet();
        $wallet->deposit(50.0); // On met des fonds

        // On change la signature : on ajoute une description
        $wallet->spend(15.0, "Cinéma avec potes");
        $wallet->spend(5.0, "McDo");

        $history = $wallet->getHistory();

        // On vérifie qu'on a bien 2 entrées
        $this->assertCount(2, $history);

        // On vérifie le contenu de la première dépense
        $this->assertSame(15.0, $history[0]['amount']);
        $this->assertSame("Cinéma avec potes", $history[0]['description']);
        // Idéalement, on testerait aussi la date, mais restons simples pour l'instant
    }

    public function testSetAllowance(): void {
        $wallet = new Wallet();

        $wallet->SetAllowance(25);
    }
}