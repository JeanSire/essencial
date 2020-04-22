<?php

namespace Classes;

use Models\ActiveRecord;

/**
 * Class ClassUser
 * @property string usrId
 * @property string usrPassword
 * @property string usrEmail
 * @property int usrLevel
 * @package Src\Classes
 */
class ClassUser extends ActiveRecord
{
    /**
     * Variável com o nome da tabela referente à esta classe.
     * @var string $table
     */
    protected $table = 'tbluser';

    /**
     * Variável com o nome do campo chave primária da tabela.
     * @var string $idField
     */
    protected $idField = 'usrId';

    /**
     * Variável que define se o id inserido será personalizado. Ex: CPF
     * @var bool $forceIdInsert
     */
    protected $forceIdInsert = true;

    /**
     * Variável que define se a tabela possui marcas de tempo.
     * @var bool $logTimestamp
     */
    protected $logTimestamp = true;
} // ClassUsers