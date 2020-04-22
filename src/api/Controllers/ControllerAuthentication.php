<?php

namespace Controllers;

use Classes\ClassClient;
use Classes\ClassUser;
use DateTime;
use Exception;
use PDOException;
use RuntimeException;
use stdClass;

use Firebase\JWT\JWT;

/**
 * Class ControllerAuthentication
 * @package Controllers
 */
class ControllerAuthentication
{

    public function login(): void
    {
        try {
            // Receber os dados da postagem
            $post = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);

            // Detectar se existe o post com os dados de usuário
            if (!isset($post['username']) || str_replace(' ', '', $post['username']) === '') {
                throw new RuntimeException('Informe o usuário!');
            } // if

            // Se o post com os dados de usuário existir
            $username = filter_var($post['username'], FILTER_SANITIZE_STRING);

            // Detectar se não existe o post com os dados de senha
            if (!isset($post['password']) || str_replace(' ', '', $post['password']) === '') {
                throw new RuntimeException('Informa a senha!');
            } // if

            // Se o post com os dados de senha existir
            $password = filter_var($post['password'], FILTER_SANITIZE_STRING);

            // Montando o filtro de pesquisa
            $filter = "WHERE `u`.`usrId` = '{$username}' AND `u`.`usrPassword` = '{$password}'";

            /**
             * Pesquisando todos os usuários no banco e atribuindo o primeiro encontrado
             * @var stdClass $authenticatedUser
             */
            $authenticatedUser = ClassUser::selectAll($filter);

            // Se o usuário for autenticado
            if (isset($authenticatedUser[0])) {
                // Exibindo a mensagem de sucesso
                echo '{
                    "status": true, 
                    "data": "Iniciando sessão...",
                    "token": "'.$this->createToken(
                        $authenticatedUser[0]->usrId,
                        $authenticatedUser[0]->usrName,
                        $authenticatedUser[0]->usrLevel
                    ).'"
                }'; // echo
                // Se o usuário e senha estiverem incorretos ou não existir
            } else {
                throw new RuntimeException('Usuário ou senha incorretos!');
            } // else
        } catch (PDOException $PDOException) {
            echo '{
                "status": false, 
                "data": "Código [' . $PDOException->getCode() . ' - ' . $PDOException->getLine() . ']' . $PDOException . '"
            }'; // echo
        } catch (Exception $exception) {
            echo '{
                "status": false, 
                "data": "' . $exception->getMessage() . '"
            }'; // echo
        } // catch
    } // login

    public function register(): void
    {
        try {
            // Receber os dados da postagem
            $post = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);

            // Se não houver e-mail na postagem
            if (!isset($post['registerEmail']) || str_replace(' ', '', $post['registerEmail']) === '') {
                throw new RuntimeException('Informe o e-mail!');
            } //if

            // Instanciando a classe de cliente e de usuário
            $user = new ClassUser();
            $client = new ClassClient();

            // Se o post com os dados de email existir
            $user->usrEmail = filter_var($post['registerEmail'], FILTER_SANITIZE_STRING);

            // Se não houver a senha na postagem
            if (!isset($post['registerPassword']) || str_replace(' ', '', $post['registerPassword']) === '') {
                throw new RuntimeException('Informe a senha!');
            } //if

            // Se não houver a confirmação da senha na postagem
            if (!isset($post['registerPasswordRepeat']) || str_replace(' ', '', $post['registerPasswordRepeat']) === '') {
                throw new RuntimeException('Informe a confirmação da senha!');
            } //if

            // Se as senhas não forem idênticas
            if ($post['registerPassword'] !== $post['registerPasswordRepeat']) {
                throw new RuntimeException('As senhas não coincidem!');
            } //if

            // Se o post com os dados de senha existir e as senhas forem idênticas
            $user->usrPassword = filter_var($post['registerPassword'], FILTER_SANITIZE_STRING);

            // Se não houver nome na postagem
            if (!isset($post['registerName']) || str_replace(' ', '', $post['registerName']) === '') {
                throw new RuntimeException('Informe o nome!');
            } //if

            // Se o post com os dados do nome existir
            $client->cltName = filter_var($post['registerName'], FILTER_SANITIZE_STRING);

            // Se não houver CPF na postagem
            if (!isset($post['registerCPF']) || str_replace(' ', '', $post['registerCPF']) === '') {
                throw new RuntimeException('Informe o CPF!');
            } //if

            // Se o post com os dados do CPF existir
            $client->cltId = filter_var($post['registerCPF'], FILTER_SANITIZE_STRING);

            // Se não houver data de nascimento na postagem
            if (!isset($post['registerBirthDate']) || str_replace(' ', '', $post['registerBirthDate']) === '') {
                throw new RuntimeException('Informe a data de nascimento!');
            } //if

            // Se o post com os dados de data de nascimanto existir
            $client->cltBirthDate = filter_var($post['registerBirthDate'], FILTER_SANITIZE_STRING);

            // Se não houver CEP na postagem
            if (!isset($post['registerCEP']) || str_replace(' ', '', $post['registerCEP']) === '') {
                throw new RuntimeException('Informe o CEP!');
            } //if

            // Se o post com os dados do CEP existir
            $client->cltCEP = filter_var($post['registerCEP'], FILTER_SANITIZE_STRING);

            // Se não houver o contato na postagem
            if (!isset($post['registerContact']) || str_replace(' ', '', $post['registerContact']) === '') {
                throw new RuntimeException('Informe o contato!');
            } //if

            // Se o post com os dados de contato existir
            $client->cltContact = filter_var($post['registerContact'], FILTER_SANITIZE_STRING);

            // Se não houver o gênero na postagem
            if (!isset($post['registerGender']) || str_replace(' ', '', $post['registerGender']) === '') {
                throw new RuntimeException('Informe o gênero!');
            } //if

            // Se o post com os dados de senha existir
            $client->cltGender = filter_var($post['registerGender'], FILTER_SANITIZE_STRING);

            // Se o e-mail já estiver sendo utulizado
            if (ClassUser::selectAll("WHERE `usrEmail` = '{$user->usrEmail}'")['status']) {
                throw new RuntimeException('Este e-mail já está sendo utilizado!');
            } // if

            if ($user->create() && $client->create()) {
                echo '{
                    "status": true,
                    "data": "Cadastro efetuado com sucesso!"       
                }'; // echo
            } else {
                echo '{
                    "status": false,
                    "data": "Ocorreu um erro durante o cadastro! Tente novamente mais tarde."
                }'; // echo
            } // else
        } catch (PDOException $pdo_exception) {
            echo '{
                "status": false, 
                "data": "Código [' . $pdo_exception->getCode() . ' - ' . $pdo_exception->getLine() . ']' . $pdo_exception . '"
            }'; // echo
        } catch (Exception $exception) {
            echo '{
                "status": false, 
                "data": "'.$exception->getMessage().'"
            }'; // echo
        } // catch
    } // register

    /**
     * @param string $uid
     * @param string $username
     * @param string $user_level
     * @return string
     * @throws Exception
     */
    public function createToken(string $uid, string $username, $user_level): string
    {
        $token_payload = [
            'iss' => 'http://srvfat:3000',
            'iat' => (new DateTime('now'))->getTimestamp(),
            'exp' => (new DateTime('now'))->getTimeStamp() + (30 * 24 * 60 * 60),
            'sub' => $uid,
            'username' => $username,
            'userLevel' => $user_level
        ]; // token_payload

        return JWT::encode($token_payload, '@Fu73b0lse secret key');
    } // authenticationJWT

    public function verifyToken(): void
    {
        $headers = [];

        // Obtendo os dados do Header enviados através da requisição
        foreach (apache_request_headers() as $header => $value) {
            $headers[$header] = $value;
        } // foreach

        $newToken = explode(' ', $headers['Authorization'])[1];

        print_r(JWT::decode($newToken, '@Fu73b0lse secret key', ['HS256']));
    } // tokenVerifyer
} // ClassLogin