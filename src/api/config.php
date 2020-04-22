<?php
// Diretório onde o projeto se encontra
$internal_folder = 'essencialavida/essencial/src/api/';

// Modo de desenvolvimento (true), modo de produção (false)
define('DEBUG', true);

// Diretório raiz host
define('DIR_PAGE', "http://{$_SERVER['HTTP_HOST']}/{$internal_folder}");

// Dirtório raiz físico
substr($_SERVER['DOCUMENT_ROOT'], -1) === '/' ?
    define('DIR_REQ',"{$_SERVER['DOCUMENT_ROOT']}{$internal_folder}"):
    define('DIR_REQ',"{$_SERVER['DOCUMENT_ROOT']}/{$internal_folder}");

// Diretórios públicos
define('DIR_IMG', DIR_PAGE . 'Public/img/');
define('DIR_CSS', DIR_PAGE . 'Public/css/');
define('DIR_JS', DIR_PAGE . 'Public/js/');
define('DIR_DESIGN', DIR_PAGE . 'Public/design/');
define('DIR_UPLOADS', DIR_PAGE . 'Public/uploads/');

// Diretórios internos
define('DIR_VENDOR', DIR_PAGE.'Src/Vendor/');

// Configuração do banco de dados
define('DATABASE', [
    'sgdb' => 'mysql',
    'host' => 'localhost',
    'port' => '3306',
    'name' => 'dbessencial',
    'user' => 'root',
    'pass' => '',
    'options' => [
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ] // options
]); // define

// Chave de autenticação do token de autenticação
define('TOKEN_SECRET_KEY', 'eqpoda_123');

// Configuração de exibição de erros
// Se o modo produção estiver habilitado, esconde todos os erros do usuário
if (!defined('DEBUG') || DEBUG === false) {
    error_reporting(0);
    ini_set('display_errors', 0);
// Se o modo debug estiver habilitado, mostra todos os erros do usuário
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} // else