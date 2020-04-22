<?php

namespace Classes;

use Traits\TraitUrlParser;

/**
 * Class ClassRoutes
 * @package Src\Classes
 */
abstract class ClassRoutes
{
    use TraitUrlParser;

    /**
     * Responsável por receber todas as rotas do sistema em um vetor
     * array('Url' => 'Arquivo Correspondente em App/Controller');
     *
     * e.g. 'home' => 'ControllerHome'
     * redireciona para App/Controllers/ControllerHome.php
     *
     * @var array $route
     */
    protected $route;

    /** ClassRoutes constructor. */
    public function __construct() {
        // Configuração de rotas existentes, para cada página deve existir um Controller
        $this->setRoute(
            array(// "Url" => "Arquivo Correspondente em App/Controllers"
                /**
                * Url Vazia
                * Caso a url esteja vazia, o servidor deve redirecioná-lo para a página inicial.
                */
                '' => 'ControllerDefault',

                /**
                * 401 Unauthorized - error401
                * Embora o padrão HTTP especifique "unauthorized", semanticamente, essa resposta significa
                * "unauthenticated". Ou seja, o cliente deve se autenticar para obter a resposta solicitada.
                */
                'erro401' => 'Controller401',

                /**
                * 403 Forbidden - error403
                * O cliente não tem direitos de acesso ao conteúdo portanto o servidor está rejeitando dar a resposta.
                * Diferente do código 401, aqui a identidade do cliente é conhecida.
                */
                'erro403' => 'Controller403',

                /**
                * 404 Not Found - error404
                * O servidor não pode encontrar o recurso solicitado.
                */
                'erro404' => 'Controller404',

                /**
                * 405 Method Not Allowed - error405
                * O método de solicitação é conhecido pelo servidor, mas foi desativado e não pode ser usado.
                */
                'erro405' => 'Controller405',

                /**
                * 501 Not Implemented - error501
                * O método da requisição não é suportado pelo servidor e não pode ser manipulado.
                */
                'erro501' => 'Controller501',

                /**
                * 503 Service Unavailable - error503
                * O servidor não está pronto para manipular a requisição.
                * Causas comuns são um servidor em manutenção ou sobrecarregado.
                */
                'erro503' => 'Controller503',

                'home' => 'ControllerDefault',

                /**
                 * Autenticação do usuário - authentication
                 */
                'authentication' => 'ControllerAuthentication'
            ) // array
        ); // setRoute
    } // __construct

    /**
     * Método responsável por tratar a requisição via url e retornar o controlador se ele existir,
     * se o controlador não existir, retorna o erro 404, e se o arquivo não existir, retorna para a página inicial.
     * @return string
     */
    public function getRoute(): string
    {
        // Recebe o índice 0 da url, responsável por definir o Controller
        $index = $this->parseUrl()[0];

        // Verifica se existe o índice no vetor de configuração de rotas
        if (array_key_exists($index, $this->route)) {
            // Verifica de o arquivo realmente existe
            if (file_exists(DIR_REQ."Controllers/{$this->route[$index]}.php")) {
                // Retorna o arquivo da rota solicitada na url
                return $this->route[$index];
            } // if

            // Se não existir um arquivo correspondente à rota, retorne a página inicial
            return 'ControllerDefault';
        }// if

        // Se não existir o índice na configuração, retorne o erro 404
        return 'Controller404';
    } // getRoute

    /** @param array $route */
    public function setRoute(array $route): void { $this->route = $route; }
} // ClassRoutes