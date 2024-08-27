<?php

namespace Alura\Leilao\tests\Model;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use PHPUnit\Framework\TestCase;

class LeilaoTest extends TestCase
{
    public static function geraLances()
    {
        $joao = new Usuario('joao');
        $maria = new Usuario('maria');
        $leilaoCom1 = new Leilao('Leilão fiat uno');
        $leilaoCom1->recebeLance(new Lance($joao, 1000));

        $leilaoCom2 = new Leilao('Leilão fiat uno');
        $leilaoCom2->recebeLance(new Lance($joao, 1000));
        $leilaoCom2->recebeLance(new Lance($maria, 1500));
        return [
            '1 lance' => [1, $leilaoCom1, [1000]],
            '2 lances' => [2, $leilaoCom2, [1000, 1500]]
        ];
    }

    /**
     * @dataProvider geraLances 
     */
    public function testLeilaoDeveReceberLances(
        int $qtdLances,
        Leilao $leilao,
        array $valores
    ) {
        self::assertCount($qtdLances, $leilao->getLances());


        foreach ($valores as $index => $valor) {
            self::assertEquals($valor, $leilao->getLances()[$index]->getValor());
        }
    }


    public function testLeilaoNaoDeveReceberLancesRepetidos()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage("O usuário não pode dar dois lances consecutivos");
        $joao = new Usuario('joao');
        $leilao = new Leilao('Leilão fiat uno');
        $leilao->recebeLance(new Lance($joao, 1000));
        $leilao->recebeLance(new Lance($joao, 1500));
    }

    public function testLeilaoNaoDeveReceberMaisDeCincoLances()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage("O usuário já deu cinco lances");
        $joao = new Usuario('joao');
        $maria = new Usuario('maria');
        $leilao = new Leilao('Leilão fiat uno');
        $leilao->recebeLance(new Lance($joao, 1000));
        $leilao->recebeLance(new Lance($maria, 1500));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($joao, 3000));
        $leilao->recebeLance(new Lance($maria, 3500));
        $leilao->recebeLance(new Lance($joao, 4000));
        $leilao->recebeLance(new Lance($maria, 4500));
        $leilao->recebeLance(new Lance($joao, 5000));
        $leilao->recebeLance(new Lance($maria, 5500));
        $leilao->recebeLance(new Lance($joao, 6000));
    }
    public function testLeilaoFinalizadoNaoPodeAceitarOutroLance()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage("O leilão já foi finalizado");
        $leilao = new Leilao("Leilão de um fiat uno");
        $jhonattan = new Usuario("Jhonattan");
        $ana = new Usuario("Ana");
        $leilao->recebeLance(new Lance($jhonattan, 9000));
        $leilao->finalizar();
        $leilao->recebeLance(new Lance($ana, 10000));
    }
}
