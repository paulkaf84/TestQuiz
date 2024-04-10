<?php

namespace App\Models;

use App\Exception\FactureVideException;
use App\Exception\MontantInfException;
use App\Exception\MontantInvalideException;
use App\Exception\MontantSupException;
use App\Exception\ReductionException;
use App\Exception\StockException;

const NOT_IN_THE_BILL = -1;
const MIN_RATE = 0;
const MAX_RATE = 100;

class Facture
{
    public function __construct(
        public int $num,
        public \DateTime $date,
        private float $montant_paye,
        private float $reduction_taux = 0,
        private array $articles = array(),
        private array $quantites = array(),
        private Client $client = new Client("", "")
    )
    {
    }

    /**
     * @throws StockException
     */
    public function ajouterArticle(Article $article, int $quantite): self
    {
        if ($quantite > $article->getStock()) {
            throw new StockException();
        }


        if ($this->isArticleInTheBill($article)) {
            $this->quantites[$article->designation] += $quantite;
            $article->setStock($article->getStock() - $quantite);

            if ($article->getPrice() != $this->articles[$this->getArticleKey($this->articles,$article)]->getPrice()) {
                $this->articles[$this->getArticleKey($this->articles ,$article)]->setPrice($article->getPrice());
            }
        } else {
            $this->articles[] = $article;
            $this->quantites[$article->designation] = $quantite;
        }


        return $this;
    }

    private function isArticleInTheBill(Article $article): bool
    {
        foreach ($this->articles as $item) {
            if ($article->designation == $item->designation) return true;
        }
        return false;
    }

    public static function getArticleKey(array $articles, Article $article)
    {
        foreach ($articles as $key => $item) {
            if ($article->designation == $item->designation) return $key;
        }
        return NOT_IN_THE_BILL;
    }

    public function getTotal(): float
    {
        $montant = 0;
        foreach ($this->articles as $key => $article) {
            $montant += $article->getPrice() * $this->quantites[$article->designation];
        }
        return $montant;
    }

    public static function getDiscount($price, $rate)
    {
        return $price - ($price * $rate);
    }

    public function setPaiement(float $montant_verse): self
    {
        if (empty($this->articles)) throw new FactureVideException();
        $this->montant_paye = $montant_verse;

        if ($montant_verse < 0) throw new MontantInvalideException();

        if ($montant_verse > $this->getTotal()) throw new MontantSupException();

        elseif ($montant_verse < $this->getTotal()) throw new MontantInfException();
        return $this;
    }

    public function setReduction(float $taux): self
    {
        if ($taux < MIN_RATE or $taux > MAX_RATE) throw new ReductionException();

        $this->reduction_taux = $taux;
        return $this;
    }

    /**
     * @return float
     */
    public function getReductionTaux(): float
    {
        return $this->reduction_taux;
    }

    /**
     * @return array
     */
    public function getArticles(): array
    {
        return $this->articles;
    }

    /**
     * @return array
     */
    public function getQuantites(): array
    {
        return $this->quantites;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    public function setClient(Client $client): self
    {
        $this->client = $client;
        return $this;
    }
}