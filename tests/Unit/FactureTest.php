<?php

use App\Exception\FactureVideException;
use App\Exception\MontantInfException;
use App\Exception\MontantSupException;
use App\Exception\ReductionException;
use App\Exception\StockException;
use App\Models\Facture;

beforeEach(function() {
    $this->bill = new Facture(1, new DateTime(), 0, 0);

    $this->mango = new \App\Models\Article('mango', 500, 10);
    $this->banana = new \App\Models\Article('banana', 700, 10);
    $this->apple = new \App\Models\Article('apple', 1_000, 10);

    $this->clientCash= new \App\Models\Client('Admin', "CASH");
    $this->clientAbonne= new \App\Models\Client('Admin', "ABONNE");
});

it('test adding an new article that already are on the the bill having the same price', function () {
    $this->bill
        ->ajouterArticle($this->mango, 5)
        ->ajouterArticle($this->mango, 5);

    expect($this->bill->getQuantites()[$this->mango->designation])->toBe(10);
});

it('test adding an new article that already are on the the bill having the a different price', function () {
    $this->bill
        ->ajouterArticle($this->mango, 5);

    $this->mango->setPrice(200);
    $this->bill
        ->ajouterArticle($this->mango, 5);

    expect($this->bill->getArticles()[Facture::getArticleKey($this->bill->getArticles(), $this->mango)]->getPrice())->toBe(200.0);
});

it('throw an exception when qty added is more than qty in stock', function () {
    $this->bill->ajouterArticle($this->mango, 11);
})->throws(StockException::class);


it('test a correct discount', function () {
    $discount = $this->bill->setReduction(5);
    expect($this->bill->getReductionTaux())->toEqual(5);
});

it('throw ReductionException with a bad discount', function () {
    $this->bill->setReduction(-1);
})->throws(ReductionException::class);

it('throw an FactureVideException', function () {
    $this->bill->setPaiement(1200);
})->throws(FactureVideException::class);

it('throw an Exception', function () {
    $this->bill->setClient($this->clientCash)
        ->ajouterArticle($this->mango, 1)
    ->setPaiement(501);

})->throws(MontantSupException::class);

it('throw an Exception inf', function () {
    $this->bill->setClient($this->clientCash)
        ->ajouterArticle($this->mango, 1)
    ->setPaiement(459);

})->throws(MontantInfException::class);
