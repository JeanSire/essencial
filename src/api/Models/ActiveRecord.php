<?php

namespace Models;

use PDO;
use Exception;
use RuntimeException;

/**
 * Class ActiveRecord
 * @package Models
 */
abstract class ActiveRecord
{
    /** @var array $content */
    private $content;
    protected $table;
    protected $idField;
    protected $forceIdInsert = false;
    protected $logTimestamp;

    /** ActiveRecord constructor. */
    public function __construct()
    {
        // Se a marca de tempo não for informada
        if (!is_bool($this->logTimestamp)) {
            // Define a marca de tempo padrão como verdadeiro
            $this->logTimestamp = true;
        } // if

        // Se a tabela não for informada
        if ($this->table === null) {
            // Define a tabela padrão como o nome da classe
            $thisClass = explode('\\', get_class($this));
            $this->table = strtolower(end($thisClass));
        } // if

        // Se o a chave primária não for informada
        if ($this->idField === null) {
            // Define a chave primária como 'id'
            $this->idField = 'id';
        } // if
    } // __construct

    /**
     * Método para definir um parâmetro e um valor ao parâmetro em $content
     * @param $parameter
     * @param $value
     */
    public function __set($parameter, $value)
    {
        $this->content[$parameter] = $value;
    } // __set

    /**
     * Método para obeter o valor de um parâmetro de $content
     * @param $parameter
     * @return mixed
     */
    public function __get($parameter)
    {
        return $this->content[$parameter];
    } // __get

    /**
     * Método para verificação de um parâmetro em $content
     * @param $parameter
     * @return bool
     */
    public function __isset($parameter)
    {
        return isset($this->content[$parameter]);
    } // __isset

    /**
     * Método para remover um parâmetro de $content.
     * @param $parameter
     * @return bool
     */
    public function __unset($parameter)
    {
        // Se o parâmetro existir
        if (isset($parameter)) {
            unset($this->content[$parameter]);
            return true;
        // Se o parâmetro não existir
        }

        return false; // else
    } // _unset

    /**
     * Ao clonar o objeto, não permitir que o id seja clonado também.
     */
    private function __clone()
    {
        if (isset($this->content[$this->idField])) {
            unset($this->content[$this->idField]);
        }
    }

    /**
     * Método para obter o conteúdo de $content em forma de vetor.
     * @return array
     */
    public function toArray(): array
    {
        return $this->content;
    } // toArray

    /**
     * Método para adicionar contrúdo em formato de vetor em $content.
     * @param array $array
     */
    public function fromArray(array $array): void
    {
        $this->content = $array;
    } // fromArray

    /**
     * Método para obter o conteúdo de $content em formato JSON.
     * @return array
     */
    public function toJson(): array
    {
        return json_encode($this->content, JSON_THROW_ON_ERROR);
    } // toJson

    /**
     * Método para adicionar contrúdo em formato JSON em $content.
     * @param string $json
     */
    public function fromJson(string $json): void
    {
        $this->content = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    } // fromJson

    /**
     * Método para impedir um atributo de possuir valor nulo, no momento de se concatenar a string para gerar a query,
     * impedir de fazer algo como $concatenar = “string” . null, ou seja, concatenar uma string com um valor nulo, ou
     * concaternar uma string com um valor booleano.
     * @param $value
     * @return string
     */
    private function format($value): ?string
    {
        // Se o parâmetro conter texto e estiver vazio
        if (is_string($value) && !empty($value)) {
            // Colocando aspas no valor para evitar concatenação com valor nulo
            return "'" . addslashes($value) . "'";
        } // if

        // Se o parâmetro conter um valor booleano
        if (is_bool($value)) {
            // Transformando valor primitivo booleano em texto
            return $value ? 'true' : 'false';
        } // if

        // Se o valor for um texto que não está vazio
        if ($value !== '') {
            // Apenas retornando o valor
            return $value;
        } // if

        // Em algum caso adverso, retorne o texto nulo
        return 'null'; // else
    } // format

    /**
     * Método para percorrer o atributo $content e verificar com a função is_scalar() se o valor contido para cada
     * elemento é válido, contendo um dos modos primitivos (integer, float, string ou boolean).
     *
     * Se possuir os tipos não escalares (array, object e resource) ignora. Sendo dados escalares, este método irá
     * consumir o método format() para ajustar ao padrão que pode ser concatenado em uma string.
     *
     * @return array
     */
    private function convertContent(): array
    {
        $newContent = array();

        // Para cara item do vetor
        foreach ($this->content as $key => $value) {
            // Se for um tipo primitivo, atribua o valor, se for um vetor, objeto ou resource não adicione
            if (is_scalar($value) && !empty($value)) {
                // Atribuindo o valor ao novo vetor
                $newContent[$key] = $this->format($value);
            } // if
        } // foreach

        return $newContent;
    } // convertContent

    /**
     * Método para inserir um registro no banco de dados.
     * Prmeiro converte e filtra os atributos do objeto e depois monta a declaração apropriada.
     *
     * @return bool
     * @throws Exception
     */
    public function create(): bool
    {
        // Formata e converte o conteúdo
        $newContent = $this->convertContent();

        // Se a marca de tempo estiver habilitada, registre o evento de criação e atualização
        if ($this->logTimestamp === true) {
            // Alterando o padrão de horário
            date_default_timezone_set('America/Sao_Paulo');

            $newContent['created_at'] = '\''.date('Y-m-d H:i:s').'\'';
            $newContent['updated_at'] = '\''.date('Y-m-d H:i:s').'\'';
        } // if

        $stmt = 'INSERT INTO '.DATABASE['name'].".`{$this->table}` ";
        $stmt .= '('.implode(', ', array_keys($newContent)).')';
        $stmt .= ' VALUES ('.implode(', ', $newContent).');';

        // Se a conexão com o banco de dados ocorrer com êxito
        if ($connection = ClassDatabase::getInstance()) {
            // Se a inserção/atualização ocorrer com sucesso
            if ($connection->exec($stmt)) {
                return true;
            } // if

            // Se a inserção/atualização falhar
            // Lançar exceção e interromper o script
            throw new RuntimeException('Não foi fossível registrar os dados!');
        } // if

        // Se não for possível conectar-se ao banco de dados
        throw new RuntimeException('Não foi fossível realizar a conexão com Banco de dados!');
    } // create

    /**
     * @return bool
     * @throws Exception
     */
    public function update(): bool
    {
        // Formata e converte o conteúdo
        $newContent = $this->convertContent();

        // Se o id for informado
        if (isset($this->content[$this->idField])) {
            // Parâmetros a serem utilizados em SET
            $sets = array();

            // Para cada parâmetro do vetor
            foreach ($newContent as $key => $value) {
                // Se o parâmetro for o id, retorne para foreach
                if ($key === $this->idField) {
                    continue;
                }
                // Se não, coloque o parâmetro na declaração SET
                $sets[] = '`' . $this->table[3] . "`.`{$key}` = {$value}";
            } // foreach

            // Se a marca de tempo estiver habilitada, registre o evento de atualização
            if ($this->logTimestamp === true) {
                // Alterando o padrão de horário
                date_default_timezone_set('America/Sao_Paulo');
                
                $sets[] = '`updated_at` = \'' . date('Y-m-d H:i:s') . '\'';
            } // if

            // Criando uma declaração de atualização
            $stmt = 'UPDATE `' . DATABASE['name'] . "`.`{$this->table}` `{$this->table[3]}`";
            $stmt .= ' SET ' . implode(', ', $sets);
            $stmt .= " WHERE `{$this->table[3]}`.`{$this->idField}` = {$this->content[$this->idField]};";

            // Se a conexão com o banco de dados ocorrer com êxito
            if ($connection = ClassDatabase::getInstance()) {
                // Se a inserção/atualização ocorrer com sucesso
                if ($connection->exec($stmt)) {
                    return true;
                } // if

                // Se a inserção/atualização falhar
                // Lançar exceção e interromper o script
                throw new RuntimeException('Não foi fossível registrar os dados!'); // else
            } // if
            // Se não for possível conectar-se ao banco de dados
            // Lançar exceção e interromper o script
            throw new RuntimeException('Não foi fossível realizar a conexão com Banco de dados!'); // else
        } // if
        // Se o id não for informado
        // Lançar exceção e interromper o script
        throw new RuntimeException('Identificador não informado!');
    } // update

    /**
     * Método para fazer a pesquisa de um registro no banco de dados de acordo com um parâmetro definido.
     * @param $parameter
     * @return object|null
     * @throws Exception
     */
    public static function find($parameter): ?object
    {
        // Recebe a classe que chamou o método
        $class = static::class;
        // Usando o resultado para gerar instancias somente em tempo de execução, a fim de obter os parâmetros.
        $idField = (new $class())->idField;
        $table = (new $class())->table;

        // $table === null ? strtolower($class) : $table) //// == //// $table ?? strtolower($class)
        // Gerando a declaração de seleção
        $stmt = 'SELECT * FROM '.DATABASE['name'].'.`'.($table ?? strtolower($class))."` `{$table[3]}`";
        $stmt .= ' WHERE '.($idField === null ? "`{$table[3]}`.`id`" : "`{$table[3]}`.`{$idField}`")." = {$parameter};";

        // Se for possível realizar a conexão com o banco de dados
        if ($connection = ClassDatabase::getInstance()) {
            // Executa a declaração criada anteriormente
            $result = $connection->query($stmt);
            // Se algum registro for encontrado
            if ($result->rowCount() > 0) {
                // Retorna o resultado em um objeto
                return $result->fetchObject(static::class);
            }

            // Se nenhum registro for encontrado
            return null;
        }
        // Se não for possível realizar a conexão no banco de dados
        // Lançar exceção e interromper o script
        throw new RuntimeException('Não há conexão com Banco de dados!');
    } // find

    /**
     * Deleta um registro do banco de dados de acordo com o id informado.
     * @return bool
     * @throws Exception
     */
    public function delete(): bool
    {
        // Se o id do registro a ser deletado for informado
        if (isset($this->content[$this->idField])) {
            // Criando uma declaração para apagar um registro
            $stmt = 'DELETE FROM '.DATABASE['name'].".`{$this->table}` `{$this->table[3]}` WHERE";
            $stmt .= " `{$this->table[3]}`.`{$this->idField}` = {$this->content[$this->idField]};";

            // Se for possível realizar a conexão com o banco de dados
            if ($connection = ClassDatabase::getInstance()) {
                // Retorna o resultado verdadeiro
                return $connection->exec($stmt);
            }

            // Se não for possível conectar-se ao banco de dados
            // Lançar exceção e interromper o script
            throw new RuntimeException('Não há conexão com Banco de dados!');
        }

        // Se o id do registro a ser removido não for informado
        return false;
    } // delete

    /**
     * Método para obter todos os registros de uma tabela do banco de dados de acordo com os filtros especificados.
     * @param string $filter
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws Exception
     */
    public static function selectAll(string $filter = '', int $limit = 0, int $offset = 0): array
    {
        // Recebe a classe que chamou o método
        $class = static::class;
        // Usando o resultado para gerar instancias somente em tempo de execução, a fim de obter os parâmetros.
        $table = (new $class())->table;

        // $table === null ? strtolower($class) : $table //// == //// $table ?? strtolower($class)
        // Criando uma declaração de seleção com filtro especificado nos parâmetros
        $stmt = 'SELECT * FROM '.DATABASE['name'].'.`'.($table ?? strtolower($class))."` `{$table[3]}` ";
        $stmt .= $filter;
        $stmt .= ($limit > 0) ? " LIMIT {$limit}" : '';
        $stmt .= ($offset > 0) ? " OFFSET {$offset}" : '';
        $stmt .= ';';

        // Se for possível realizar a conexão com o banco de dados
        if ($connection = ClassDatabase::getInstance()) {
            // Retorna todos os registros encontrados
            $result = $connection->query($stmt);

            // Se algum registro for encontrado
            if ($result->rowCount() > 0) {
                return ['status' => true, 'data' => $result->fetchAll(PDO::FETCH_CLASS)];
            } // if

            // Se nenhum registro for encontrado
            return ['status' => false, 'data' => 'Nenhum registro encontrado!'];
        } // if

        // Se não for possível conectar-se ao banco de dados
        // Lançar exceção e interromper o script
        throw new RuntimeException('Não há conexão com Banco de dados!');
    } // selectAll
} // ActiveRecord