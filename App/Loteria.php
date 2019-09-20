<?php

namespace App;

/**
 * Loteria class
 *
 * @author Jannsen <jannsen.bgo@gmail.com>
 */
class Loteria
{
    private $quantidadeDezenas;
    private $resultado;
    private $totalJogos;
    private $jogos;

    /**
     * Construtor da classe
     *
     * @param int $quantidadeDezenas
     * @param int $totalJogos
     * @author Jannsen <jannsen.bgo@gmail.com>
     */
    public function __construct(int $quantidadeDezenas, int $totalJogos)
    {
        $this->setQuantidadeDezenas($this->quantidadeDezenasValida($quantidadeDezenas) ? $quantidadeDezenas : 0);
        $this->setTotalJogos($totalJogos);
    }

    /**
     * Gerando a aposta considerando a quantidade de dezenas definida
     *
     * @return array
     * @author Jannsen <jannsen.bgo@gmail.com>
     */
    private function gerarAposta() : array
    {
        $dezenas = [];
        if (!\is_null($this->getQuantidadeDezenas()))
        {
            while (\count($dezenas) < $this->getQuantidadeDezenas())
            {
                $this->gerarDezenasAleatorias($dezenas);
            }
            \sort($dezenas);
        }
        return $dezenas;
    }

    /**
     * Realiza a geração dos jogos considerando o total de jogos
     * definido
     *
     * @return self
     * @author Jannsen <jannsen.bgo@gmail.com>
     */
    public function gerarJogos() : self
    {
        $jogos = [];
        if ($this->getTotalJogos() > 0)
        {
            while (\count($jogos) < $this->getTotalJogos())
            {
                $dezenas = $this->gerarAposta();
                if (!\count($dezenas))
                {
                    break;
                }
                \array_push($jogos, $dezenas);
            }
        }
        $this->setJogos($jogos);
        return $this;
    }

    /**
     * Realizando o sorteio
     *
     * @return self
     * @author Jannsen <jannsen.bgo@gmail.com>
     */
    public function realizaSorteio() : self
    {
        $resultado = [];
        while (\count($resultado) < 6)
        {
            $this->gerarDezenasAleatorias($resultado);
        }
        \sort($resultado);
        $this->setResultado($resultado);
        return $this;
    }

    /**
     * Metodo auxiliar para gerar dezenas aleatórias e armazenar em um array
     *
     * @param array $arrayDestino
     * @return void
     * @author Jannsen <jannsen.bgo@gmail.com>
     */
    private function gerarDezenasAleatorias(array &$arrayDestino)
    {
        $dezena = \rand(1, 60);
        if (!\in_array($dezena, $arrayDestino))
        {
            \array_push($arrayDestino, $dezena);
        }
    }

    /**
     * Validando se a quantidade de dezenas informada está dentro dos valores permitidos
     *
     * @param int $quantidadeDezenas
     * @return bool
     * @author Jannsen <jannsen.bgo@gmail.com>
     */
    private function quantidadeDezenasValida(int $quantidadeDezenas) : bool
    {
        return \in_array($quantidadeDezenas, \range(6,10));
    }

    /**
     * Conferindo a quantidade de acertos nas apostas realizadas
     *
     * @return string
     * @author Jannsen <jannsen.bgo@gmail.com>
     */
    public function confereJogos() : string
    {
        $dadosTemplate = [];
        if (\count($this->getResultado()) && \count($this->getJogos()))
        {
            $resultadoJogos = [];
            foreach ($this->getJogos() AS $key => $jogo)
            {
                $resultadoJogos[$key] = \array_intersect($jogo, $this->getResultado());
            }
            $dadosTemplate["resultado"]         = $this->getResultado();
            $dadosTemplate["jogos"]             = $this->getJogos();
            $dadosTemplate["quantidadeDezenas"] = $this->getQuantidadeDezenas();
            $dadosTemplate["resultadoJogos"]    = $resultadoJogos;
        }
        return $this->index($dadosTemplate);
    }

    /**
     * Renderizando a página com as informações das dezenas sorteadas
     * e quantidade de acertos
     *
     * @param array $dadosTemplate
     * @return string
     * @author Jannsen <jannsen.bgo@gmail.com>
     */
    private function index(array $dadosTemplate) : string
    {
        require __DIR__.'/../vendor/autoload.php';
        $twig = new \Twig_Environment(new \Twig_Loader_Filesystem($_SERVER["DOCUMENT_ROOT"] . '/../resources/views/'),['autoescape' => false ]);
        return $twig->render("loteria.twig", $dadosTemplate);
    }

    /**
     * Obtem o valor de $jogos
     *
     * @return array
     * @author Jannsen <jannsen.bgo@gdax.com.br>
     */
    public function getJogos() : array
    {
        return $this->jogos;
    }

    /**
     * Atribui o valor para $jogos
     *
     * @param array $jogos
     * @return void
     * @author Jannsen <jannsen.bgo@gdax.com.br>
     */
    public function setJogos(array $jogos)
    {
        $this->jogos = $jogos;
    }

    /**
     * Obtem o valor de $totalJogos
     *
     * @return int
     * @author Jannsen <jannsen.bgo@gdax.com.br>
     */
    public function getTotalJogos() : int
    {
        return $this->totalJogos;
    }

    /**
     * Atribui o valor para $totalJogos
     * @return void
     * @author Jannsen <jannsen.bgo@gdax.com.br>
     */
    public function setTotalJogos(int $totalJogos)
    {
        $this->totalJogos = $totalJogos;
    }

    /**
     * Obtem o valor de $resultado
     *
     * @return array
     * @author Jannsen <jannsen.bgo@gdax.com.br>
     */
    public function getResultado() : array
    {
        return $this->resultado;
    }

    /**
     * Atribui o valor para $resultado
     * @return void
     * @author Jannsen <jannsen.bgo@gdax.com.br>
     */
    public function setResultado(array $resultado)
    {
        $this->resultado = $resultado;
    }

    /**
     * Obtem o valor de $quantidadeDezenas
     *
     * @return int
     * @author Jannsen <jannsen.bgo@gdax.com.br>
     */
    public function getQuantidadeDezenas() : int
    {
        return $this->quantidadeDezenas;
    }

    /**
     * Atribui o valor para $quantidadeDezenas
     * @return void
     * @author Jannsen <jannsen.bgo@gdax.com.br>
     */
    public function setQuantidadeDezenas(int $quantidadeDezenas)
    {
        $this->quantidadeDezenas = $quantidadeDezenas;
    }
}