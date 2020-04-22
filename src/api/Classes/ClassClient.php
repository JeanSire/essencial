<?php

namespace Classes;

use Models\ActiveRecord;

/**
 * Class ClassClient
 * @property string cltId
 * @property string cltUser
 * @property string cltName
 * @property string cltEmail
 * @property string cltBirthDate
 * @property string cltContact
 * @property string cltGender
 * @property string cltCEP
 * @property string cltCPF
 * @property string cltSpouse
 * @property float cltWeight
 * @property float cltHeight
 * @property float cltShoulder
 * @property float cltPelvis
 * @package Classes
 */
final class ClassClient extends ActiveRecord
{
    /**
     * Variável com o nome da tabela referente à esta classe.
     * @var string $table
     */
    protected $table = 'tblclient';

    /**
     * Variável com o nome do campo chave primária da tabela.
     * @var string $idField
     */
    protected $idField = 'cltId';

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
}