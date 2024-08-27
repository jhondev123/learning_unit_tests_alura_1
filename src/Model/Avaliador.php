<?php

namespace Alura\Leilao\Model;

class Avaliador
{
    private float $maiorValor = 0;
    private float $menorValor = INF;
    private array $maioresLances = [];
    public function avalia(Leilao $leilao): void
    {
        if($leilao->getFinalizado()){
            throw new \DomainException("O leilão já foi finalizado");
        }
        if (empty($leilao->getLances())) {
            throw new \DomainException("O leilão não possui lances");
        }
        foreach ($leilao->getLances() as $lance) {
            if ($lance->getValor() > $this->maiorValor) {
                $this->maiorValor = $lance->getValor();
            }
            if ($lance->getValor() < $this->menorValor) {
                $this->menorValor = $lance->getValor();
            }
        }
        $lances = $leilao->getLances();
        usort($lances, function (Lance $lance1, Lance $lance2) {
            return $lance2->getValor() - $lance1->getValor();
        });
        $this->maioresLances = array_slice($lances, 0, 3);
    }
    public function getMaiorValor()
    {
        return $this->maiorValor;
    }
    public function getMenorValor()
    {
        return $this->menorValor;
    }
    public function getMaioresLances(): array
    {
        return $this->maioresLances;
    }
}
