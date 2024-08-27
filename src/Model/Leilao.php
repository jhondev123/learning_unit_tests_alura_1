<?php

namespace Alura\Leilao\Model;

class Leilao
{
    /** @var Lance[] */
    private $lances;
    /** @var string */
    private $descricao;

    private bool $finalizado = false;

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->lances = [];
    }

    public function recebeLance(Lance $lance)
    {
        if (!empty($this->lances) && $this->verificaSeUsuarioNaoDeuUltimoLance($lance)) {
            throw new \DomainException("O usuário não pode dar dois lances consecutivos");
        }
        if ($this->verificaSeUsuarioDeuCincoLances($lance)) {
            throw new \DomainException("O usuário já deu cinco lances");
        }
        if ($this->finalizado) {
            throw new \DomainException("O leilão já foi finalizado");
        }
        $this->lances[] = $lance;
    }

    /**
     * @return Lance[]
     */
    public function getLances(): array
    {
        return $this->lances;
    }
    public function verificaSeUsuarioNaoDeuUltimoLance(Lance $lance): bool
    {
        $ultimoLance = $this->lances[array_key_last($this->lances)];
        return $lance->getUsuario() == $ultimoLance->getUsuario();
    }
    public function finalizar()
    {
        $this->finalizado = true;
    }
    public function verificaSeUsuarioDeuCincoLances(Lance $lanceAtual): bool
    {
        $count = 0;
        foreach ($this->lances as $lance) {
            if ($lance->getUsuario() == $lanceAtual->getUsuario()) {
                $count++;
            }
        }
        if ($count >= 5) {
            return true;
        }
        return false;
    }
    public function getFinalizado():bool
    {
        return $this->finalizado;
    }
}
