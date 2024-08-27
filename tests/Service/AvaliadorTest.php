<?php

namespace Alura\Leilao\tests\Service;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use PHPUnit\Framework\TestCase;
use Alura\Leilao\Model\Avaliador;

class AvaliadorTest extends TestCase
{
    private Avaliador $avaliador;
    protected function setUp(): void
    {
        parent::setUp();
        $this->avaliador = new Avaliador();
    }

    public static function leilaoEmOrdemCrescente(): array
    {
        $leilao = new Leilao("Leilão de um fiat uno");
        $jhonattan = new Usuario("Jhonattan");
        $ana = new Usuario("Ana");
        $lucas = new Usuario("Lucas");
        $leilao->recebeLance(new Lance($ana, 7500));
        $leilao->recebeLance(new Lance($jhonattan, 8000));
        $leilao->recebeLance(new Lance($lucas, 8500));
        $leilao->recebeLance(new Lance($jhonattan, 9000));
        return [
            "ordem crescente" => [$leilao]
        ];
    }
    public static function leilaoEmOrdemDecrescente(): array
    {
        $leilao = new Leilao("Leilão de um fiat uno");
        $jhonattan = new Usuario("Jhonattan");
        $ana = new Usuario("Ana");
        $lucas = new Usuario("Lucas");
        $leilao->recebeLance(new Lance($jhonattan, 9000));
        $leilao->recebeLance(new Lance($lucas, 8500));
        $leilao->recebeLance(new Lance($jhonattan, 8000));
        $leilao->recebeLance(new Lance($ana, 7500));
        return [
            "ordem decrescente" => [$leilao]
        ];
    }

    public static function leilaoSemTestes()
    {
        $leilao = new Leilao("Leilão de um fiat uno");
        return ["leilao sem lances" => [$leilao]];
    }


    /**
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     */
    public function testAvaliadorGetMaiorValor(Leilao $leilao)
    {
        $this->avaliador->avalia($leilao);
        self::assertEquals(expected: 9000, actual: $this->avaliador->getMaiorValor());
    }
    /**
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     */
    public function testAvaliadorGetMenorValor(Leilao $leilao)
    {

        $this->avaliador->avalia($leilao);
        self::assertEquals(expected: 7500, actual: $this->avaliador->getMenorValor());
    }

    /**
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     */
    public function testAvaliadorGet3MaioresValores(Leilao $leilao)
    {
        $this->avaliador->avalia($leilao);
        self::assertCount(3, $this->avaliador->getMaioresLances());
        self::assertEquals(9000, $this->avaliador->getMaioresLances()[0]->getValor());
        self::assertEquals(8500, $this->avaliador->getMaioresLances()[1]->getValor());
        self::assertEquals(8000, $this->avaliador->getMaioresLances()[2]->getValor());
    }
    /**
     * @dataProvider leilaoSemTestes
     */
    public function testLeilaoSemLancesPrecisaLancarUmaExcecao(Leilao $leilao)
    {
        $this->expectException(\DomainException::class);
        $this->avaliador->avalia($leilao);
    }
    public function testLeilaoFinalizadoNaoPodeSerAvaliado()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage("O leilão já foi finalizado");
        $leilao = new Leilao("Leilão de um fiat uno");
        $jhonattan = new Usuario("Jhonattan");
        $leilao->recebeLance(new Lance($jhonattan, 9000));
        $leilao->finalizar();
        $this->avaliador->avalia($leilao);
    }
}
