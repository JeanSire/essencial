<?php

namespace Classes;

/**
 * Class ClassDispatch
 * @package App
 */
class ClassDispatch extends ClassRoutes
{
    // Atributos
    /**
     * Difine os métodos passados na url.
     * @var string $Method
     */
    private $Method;

    /**
     * Define os parâmetros a serem usados na chamada do método.
     * @var array $Params
     */
    private $Params = [];

    private $Object;

    // Método Construtor, chama o Controller requisitado na url
    public function __construct()
    {
        // Chama o contrutor da superclasse
        parent::__construct();
        // Adiciona um controller
        $this->addController();
    }

    // Método de adição de Controller
    private function addController(): void
    {
        // Atribui o Controller requisitado na url à variável $Controller
        $controller = $this->getRoute();

        // Prepara a string da rota
        $controllerRoute = "Controllers\\{$controller}";

        // Instancia o Controller requisitado na url
        $this->setObject(new $controllerRoute);

        // Se houver um método na url, tente executá-lo
        if (isset($this->parseUrl()[1])) {
            $this->addMethod();
        } // if
    } // addController

    // Método de adição de método do Controller
    private function addMethod(): void
    {
        // Se o método que etiver na url existir, execute-o
        if(method_exists($this->getObject(), $this->parseUrl()[1])) {
            // Atribui o índice 1 da url ao método
            $this->setMethod((string)($this->parseUrl()[1]));
            // Adiciona parâmetros ao método
            $this->addParam();
            // Execute o método contido no Controller ([Controller, método], parâmetro do método)
            call_user_func_array([$this->getObject(), $this->getMethod()], $this->getParams());
        } // if
    } // addMethod

    // Método de adição de parâmetros do Controller
    private function addParam(): void
    {
        // Retorna a quantidade de índices na url
        $countArray = count($this->parseUrl());

        // Verifica se há parâmetro na url => https://www.site.com/controlador/metodo/parametro
        if ($countArray > 2) {
            // Para cada índice da url, acrescente no vetor $Param[]
            foreach ($this->parseUrl() as $index => $value) {
                // Se o índice for um parâmetro, coloque-o no vetor $Param[]
                if ($index > 1) {
                    $this->setParams($this->Params += [$index => $value]);
                } // if
            } // foreach
        } // if
    } // addParam

    // Métodos acessores dos atributos
    /** @return string */
    protected function getMethod(): string
    { return $this->Method; }

    /** @param mixed $Method */
    public function setMethod($Method): void
    { $this->Method = $Method; }

    /** @return array */
    public function getParams(): array
    { return $this->Params; }

    /** @param array $Params */
    public function setParams($Params): void
    { $this->Params = $Params; }

    /** @return mixed */
    public function getObject()
    { return $this->Object; }

    /** @param mixed $Object */
    public function setObject($Object): void
    { $this->Object = $Object; }
} // ClassDispatch