<?php

namespace Classes;

/**
 * Class ClassSession
 * @package Src\Classes
 */
class ClassSession
{
    /**
     * Verifica se o usuário está logado
     * @return void
     */
    public static function isLoggedCheck(): void
    {
        if (isset($_SESSION['usrId'])) {
            header('Location: '.DIR_PAGE.'inicio');
        }
    }

    /**
     * Verifica se o usuário não está logado
     * @return void
     */
    public static function isNotLoggedCheck(): void
    {
        if (!isset($_SESSION['usrId'])) {
            header('Location: '.DIR_PAGE.'login');
        }
    } // isNotLoggedCheck

    /**
     * Desconecta o usuário e o faz retornar para a página de login
     * @return void
     */
    public static function logout(): void
    {
        session_unset();
        session_destroy();
        header('Location: '.DIR_PAGE.'login');
    } // logout

    /**
     * @param $data
     * @return array
     */
    public static function getSession(string $data): array
    {
        // Se a sessão existir
        if (isset($_SESSION[$data])) {
            return array('status' => true, 'data' => $_SESSION[$data]);
        } // if

        // Se a sessão não existir
        return array('status' => false, 'data' => null);
    } // getSession

    /**
     * @param $user
     * @return void
     */
    public static function setSession(array $user): void
    {
        // Dados do usuário logado
        $_SESSION['usrId'] = $user['usrId'];
        $_SESSION['usrName'] = $user['usrName'];
        $_SESSION['usrLevel'] = $user['usrLevel'];
    } // setSession
} // ClassSession