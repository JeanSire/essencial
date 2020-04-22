import React, { useState, useEffect, useRef } from 'react'
import NumberFormat from 'react-number-format'
import api from '../../services/api'

import CustomInput from '../CustomInput'
import CustomRadio from '../CustomRadio'
import CustomButton from '../CustomButton'
import Modal from '../Modal'
import Toast from '../Toast'
import { login } from '../../services/auth'
import {CSSTransition, TransitionGroup} from 'react-transition-group'
import './index.css'

const LoginModal = ({ isShowing, hide }) => {

    // Login form
    const [ firstAttemp, setFirstAttemp ] = useState(true)

    const [ username, setUsername ] = useState('')
    const [ usernameHint, setUsernameHint ] = useState('')
    const [ usernameError, setUsernameError ] = useState(false)

    const [ password, setPassword ] = useState('')
    const [ passwordError, setPasswordError ] = useState(false)
    const [ passwordHint, setPasswordHint ] = useState('')

    // Register Form
    const [ firstRegisterAttemp, setFirstRegisterAttemp ] = useState(false)

    const [ registerEmail, setRegisterEmail ] = useState('')
    const [ registerEmailError, setRegisterEmailError ] = useState('')
    const [ registerEmailHint, setRegisterEmailHint ] = useState('')

    const [ registerPassword, setRegisterPassword ] = useState('')
    const [ registerPasswordError, setRegisterPasswordError ] = useState('')
    const [ registerPasswordHint, setRegisterPasswordHint ] = useState('')

    const [ registerPasswordRepeat, setRegisterPasswordRepeat ] = useState('')
    const [ registerPasswordRepeatError, setRegisterPasswordRepeatError ] = useState('')
    const [ registerPasswordRepeatHint, setRegisterPasswordRepeatHint ] = useState('')

    const [ registerName, setRegisterName ] = useState('')
    const [ registerNameError, setRegisterNameError ] = useState('')
    const [ registerNameHint, setRegisterNameHint ] = useState('')

    const [ registerCPF, setRegisterCPF ] = useState('')
    const [ registerCPFError, setRegisterCPFError ] = useState('')
    const [ registerCPFHint, setRegisterCPFHint ] = useState('')

    const [ registerBirthDate, setRegisterBirthDate ] = useState('')
    const [ registerBirthDateError, setRegisterBirthDateError ] = useState('')
    const [ registerBirthDateHint, setRegisterBirthDateHint ] = useState('')

    const [ registerCEP, setRegisterCEP] = useState('')
    const [ registerCEPError, setRegisterCEPError ] = useState('')
    const [ registerCEPHint, setRegisterCEPHint ] = useState('')

    const [ registerContact, setRegisterContact ] = useState('')
    const [ registerContactError, setRegisterContactError ] = useState('')
    const [ registerContactHint, setRegisterContactHint ] = useState('')

    const [ registerGender, setRegisterGender ] = useState('')
    const [ registerGenderError, setRegisterGenderError ] = useState('')
    const [ registerGenderHint, setRegisterGenderHint ] = useState('')

    // Modal controls
    const [ modalPage, setModalPage ] = useState(true)
    const [ isShowingToast, setIsShowingToast ] = useState(false)
    const [ toastMessage, setToastMessage ] = useState('')
    const [ toastType, setToastType ] = useState('check')

    // Adicionando uma referência para remover a necessidade de dependência dentro da função useEffect
    const firstAttempTest = useRef()
    useEffect(() => { firstAttempTest.current = firstAttemp })

    // Usuário
    useEffect(() => {
        if (!firstAttempTest.current) {
            if (username.trim() === '') {
                setUsernameError(true)
                setUsernameHint('Informe o usuário!')
            } else {
                setUsernameError(false)
                setUsernameHint('')
            } // else
        } // if
    }, [ username ]) // useEffect

    // Senha
    useEffect(() => {
        if (!firstAttempTest.current) {
            if (password.trim() === '') {
                setPasswordError(true)
                setPasswordHint('Informe a senha!')
            } else {
                setPasswordError(false)
                setPasswordHint('')
            } // else
        } // if
    }, [ password ]) // useEffect

    // Alterar a visibilidade do toast
    const toggleToast = () => setIsShowingToast(!isShowingToast)

    // Redefinir todos os campos com erro
    const resetErrors = () => {
        setRegisterEmailError(false)
        setRegisterEmailHint('')
        setRegisterPasswordError(false)
        setRegisterPasswordHint('')
        setRegisterPasswordRepeatError(false)
        setRegisterPasswordRepeatHint('')
        setRegisterNameError(false)
        setRegisterNameHint('')
        setRegisterCPFError(false)
        setRegisterCPFHint('')
        setRegisterBirthDateError(false)
        setRegisterBirthDateHint('')
        setRegisterCEPError(false)
        setRegisterCEPHint('')
        setRegisterContactError(false)
        setRegisterContactHint('')
    } // resetErrors

    /** Teste de e-mail padrão regex 99.99% ECMAScript 10 */
    const emailTest = email => {
        const emailRegex = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (emailRegex.test(email)) {
            setRegisterEmailError(true)
            setRegisterEmailHint('E-mail inválido!')
            return true
        } // if
    } // emailTest

    const passwordMatchTest = (password, passwordRepeat) => {
        if (password !== passwordRepeat) {
            setRegisterPasswordRepeatError(true)
            setRegisterPasswordRepeatHint('As senhas não coincidem!')
            setRegisterPasswordError(true)
            return true
        } //if
    } // passwordmatchTest

    /** @return {boolean} */
    const CPFTest = cpf => {
        let sum = 0;
        let left;
        let i;

        // Remove ' . ' e ' - ' do CPF
        cpf = cpf.replace(/[-.]/g, '');

        // Se o cpf estiver vazio
        if (cpf.trim() === '') {
            return false;
            // Validar o cpf
        } else {
            for (i = 1; i <= 9; i++) sum += parseInt(cpf.substring(i - 1, i)) * (11 - i)
            left = (sum * 10) % 11
            if ((left === 10) || (left === 11)) left = 0
            if (left !== parseInt(cpf.substring(9, 10))) return false
            sum = 0
            for (i = 1; i <= 10; i++) sum += parseInt(cpf.substring(i - 1, i)) * (12 - i)
            left = (sum * 10) % 11
            if ((left === 10) || (left === 11)) left = 0
            return left === parseInt(cpf.substring(10, 11))
        }
    }

    // Função de autentcação do usuario
    const authenticate = async () => {
        try {
            // Definir a pimeira tentativa de acesso
            setFirstAttemp(false)

            // se o campoo de nome estiver vazio
            if (username.trim() === '') {
                setUsernameError(true)
                setUsernameHint('Informe o usuário!')
                throw new Error('Informe o usuário!')
            } // if

            // Se a o campo de senha estiver vazio
            if (password.trim() === '') {
                setPasswordError(true)
                setPasswordHint('Informe a senha!')
                throw new Error('Informe a senha!')
            } // if

            // Requisição assíncrona de autenticação do usuário
            await api.post('authentication/login', `{
                "username": "${username}",
                "password": "${password}"
            }`).then(response => {
                // Exibir um toast com a resposta do servidor
                setToastMessage(response.data.data)

                // Se o usuário for autenticado
                if (response.data.status) {
                    // Definindo o tipo do toast para sucesso
                    setToastType('check')
                    // Armazenando o token de autenticação
                    login(response.data.token)
                    // Feedback de operação para o usuário
                    const changeToastMessage = () => setToastMessage('Sessão iniciada!')
                    setTimeout(changeToastMessage, 1400);
                    // Esconder o modal
                    hide()
                // Se as credenciais estiverem incorretas ou ocorrer algum erro no servidor
                } else {
                    // Definindo estado de erro
                    setToastType('exclamation-triangle')
                    setPasswordError(true)
                    setUsernameError(true)
                } // else

                // Alterar
                toggleToast()
            // Se ocorrer algum problema durante a requisição
            }).catch(error => {
                setToastMessage(error.message)
            }); // catch
        } catch (exception) {
            setToastMessage(exception.message)
            setToastType('exclamation-triangle')
            toggleToast()
        } // catch
    } // authenticate

    // Função de cadastro de usuário
    const register = async () => {
        // Resetar todos os campos de erro
        resetErrors()

        try {
            // Se o campo "e-mail" estiver vazio
            if (registerEmail.replace(/\s/g, '') === '') {
                setRegisterEmailError(true)
                setRegisterEmailHint('Informe o e-mail!')
                throw new Error('Informe o e-mail!')
            } // if

            // Se o e-mail estiver em um formato inválido
            if (emailTest(registerEmail)) {
                throw new Error('E-mail inválido')
            } // if

            // Se o campo senha estiver vazio
            if (registerPassword.replace(/^s+|s+$/g, '') === '') {
                setRegisterPasswordError(true)
                setRegisterPasswordHint('Informe a senha!')
                throw new Error('Informe a senha!')
            } // if

            // Se a quantidade de caractere da senha for menor que 6
            if (registerPassword.length < 6) {
                setRegisterPasswordError(true)
                setRegisterPasswordHint('A senha deve ter pelomenos 6 caracteres!')
                throw new Error('A senha deve ter pelomenos 6 caracteres!')
            } // if

            // Se o campo de confirmação da senha estiver vazio
            if (registerPasswordRepeat.replace(/^s+|s+$/g, '') === '') {
                setRegisterPasswordRepeatError(true)
                setRegisterPasswordRepeatHint('Informe a confirmação da senha!')
                throw new Error('Informe a confirmação da senha!')
            } // if

            // Se as senhas não coincidirem
            if (passwordMatchTest(registerPassword, registerPasswordRepeat)) {
                throw new Error('As senhas não coincidem!')
            } // if

            // Se o campo "nome" estiver vazio
            if (registerName.replace(/^s+|s+$/g, '') === '') {
                setRegisterNameError(true)
                setRegisterNameHint('Informe o nome!')
                throw new Error('Informe o nome!')
            } // if

            // Se o campo "CPF" estiver vazio
            if (registerCPF.replace(/^s+|s+$/g, '') === '') {
                setRegisterCPFError(true)
                setRegisterCPFHint('Informe o CPF!')
                throw new Error('Informe o CPF!')
            } // if

            // Se o CPF for inválido
            if (!CPFTest(registerCPF)) {
                setRegisterCPFError(true)
                setRegisterCPFHint('CPF inválido!')
                throw new Error('CPF inválido!')
            } // if

            // Se o campo "data de nacimento" estiver vazio
            if (registerBirthDate.replace(/^s+|s+$/g, '') === '') {
                setRegisterBirthDateError(true)
                setRegisterBirthDateHint('Informe a data de nascimento!')
                throw new Error('Informe a data de nacimento!')
            } // if

            // Se a data de nacimento for inválida
            if (!/\d{2}\/\d{2}\/\d{4}/.test(registerBirthDate)) {
                setRegisterBirthDateError(true)
                setRegisterBirthDateHint('Data de nascimento inválida!')
                throw new Error('Data de nacimento inválida!')
            } // if

            // Se o campo "CEP" estiver vazio
            if (registerCEP.replace(/^s+|s+$/g, '') === '') {
                setRegisterCEPError(true)
                setRegisterCEPHint('Informe o CEP!')
                throw new Error('Informe o CEP!')
            } // if

            // Se o CEP for inválido
            if (!/^[0-9]{2}.[0-9]{3}-[0-9]{3}$/.test(registerCEP)) {
                setRegisterCEPError(true)
                setRegisterCEPHint('CEP inválido!')
                throw new Error('CEP inválido!')
            } // if

            // Se o campo "contato" estiver vazio
            if (registerContact.replace(/^s+|s+$/g, '') === '') {
                setRegisterContactError(true)
                setRegisterContactHint('Informe um contato telefônico!')
                throw new Error('Informe um contato telefônico!')
            } // if

            if (/(\(?\d{2}\)?\s)?(\d{4,5}-\d{4})/.test(registerContact)) {

            }

            await api.post('authentication/register', `{
            "registerEmail": "${registerEmail}",
            "registerPassword": "${registerPassword}",
            "registerPasswordRepeat": "${registerPasswordRepeat}",
            "registerName": "${registerName}",
            "registerCPF": "${registerCPF}",
            "registerBirthDate": "${registerBirthDate}",
            "registerCEP": "${registerCEP}",
            "registerContact": "${registerContact}",
            "registerGender": "${registerGender}" 
        }`).then(response => {
                // Exibir um toast com a resposta do servidor
                setToastMessage(response.data.data)

                // Se o usuário for altenticado
                if (response.data.status) {
                    // Definindo o tipo do toast para sucesso
                    setToastType('check')

                    // Feedback de operação para o usuário
                    const changeToastMessage = () => setToastMessage('Sessão iniciada!')
                    setTimeout(changeToastMessage, 1400);
                    // Esconder o modal
                    hide()
                    // Se as credenciais estiverem incorretas ou ocorrer algum erro no servidor
                } else {
                    // Definindo estado de erro
                    setToastType('exclamation-triangle')
                    setPasswordError(true)
                    setUsernameError(true)
                } // else

                // Alterar
                toggleToast()
                // Se ocorrer algum problema durante a requisição
            }).catch(error => {
                setToastMessage(error.message)
            }); // catch
        } catch (error) {
            setToastMessage(error.message)
            setToastType('exclamation-triangle')
            setIsShowingToast(true)
        } // catch
    } // register

    return(
        <>
            <Modal
                isShowing={isShowing}
                hide={hide}
                header={
                    <TransitionGroup className="login-modal-header">
                        <CSSTransition
                            key={modalPage}
                            timeout={500}
                            classNames="login-modal-header"
                        >
                            {modalPage ? (
                                <h2 className="login-modal-title">Login</h2>
                            ) : (
                                <h2 className="login-modal-title">Cadastro</h2>
                            )}
                        </CSSTransition>
                    </TransitionGroup>
                }
                body={
                    <TransitionGroup className="login-modal-body">
                        <CSSTransition
                            key={modalPage}
                            timeout={500}
                            classNames="login-modal-body"
                        >
                            {modalPage ? (
                                <form action="" method="post" className="login-modal-form">
                                    <CustomInput
                                        type="text"
                                        name="username"
                                        id="username"
                                        placeholder="CPF"
                                        label="CPF: "
                                        autoComplete="username"
                                        value={username}
                                        hint={usernameHint}
                                        error={usernameError}
                                        onChange={event => setUsername(event.target.value)}
                                    />
                                    <br/>
                                    <CustomInput
                                        type="password"
                                        name="password"
                                        id="password"
                                        placeholder="Senha"
                                        label="Senha: "
                                        autoComplete="current-password"
                                        value={password}
                                        hint={passwordHint}
                                        error={passwordError}
                                        onChange={event => setPassword(event.target.value)}
                                    />
                                </form>
                            ) : (
                                <form action="" method="post" className="login-modal-form">
                                    <CustomInput
                                        type="email"
                                        name="registerEmail"
                                        id="registerEmail"
                                        placeholder="Email"
                                        label="Email: "
                                        autoComplete="email"
                                        value={registerEmail}
                                        hint={registerEmailHint}
                                        error={registerEmailError}
                                        onChange={event => setRegisterEmail(event.target.value)}
                                    />
                                    <CustomInput
                                        type="password"
                                        name="registerPassword"
                                        id="registerPassword"
                                        placeholder="Senha"
                                        label="Senha: "
                                        autoComplete="new-password"
                                        value={registerPassword}
                                        hint={registerPasswordHint}
                                        error={registerPasswordError}
                                        onChange={event => setRegisterPassword(event.target.value)}
                                    />
                                    <CustomInput
                                        type="password"
                                        name="registerPasswordRepeat"
                                        id="registerPasswordRepeat"
                                        placeholder="Repetir a senha"
                                        label="Repetir a senha: "
                                        autoComplete="new-password"
                                        value={registerPasswordRepeat}
                                        hint={registerPasswordRepeatHint}
                                        error={registerPasswordRepeatError}
                                        onChange={event => setRegisterPasswordRepeat(event.target.value)}
                                    />
                                    <br/>
                                    <CustomInput
                                        type="text"
                                        name="registerName"
                                        id="registerName"
                                        placeholder="Nome completo"
                                        label="Nome completo: "
                                        autoComplete="off"
                                        value={registerName}
                                        hint={registerNameHint}
                                        error={registerNameError}
                                        onChange={event => setRegisterName(event.target.value)}
                                    />
                                    <NumberFormat
                                        type="text"
                                        name="registerCPF"
                                        id="registerCPF"
                                        placeholder="CPF"
                                        label="CPF: "
                                        autoComplete="postal-code"
                                        format="###.###.###-##"
                                        customInput={CustomInput}
                                        value={registerCPF}
                                        hint={registerCPFHint}
                                        error={registerCPFError}
                                        onChange={event => setRegisterCPF(event.target.value.replace(/[. -]/g, ''))}
                                    />
                                    <NumberFormat
                                        type="text"
                                        name="registerBirthDate"
                                        id="registerBirthDate"
                                        placeholder="Data de nacimento"
                                        label="Data de nascimento: "
                                        autoComplete="bday"
                                        format="##/##/####"
                                        customInput={CustomInput}
                                        value={registerBirthDate}
                                        hint={registerBirthDateHint}
                                        error={registerBirthDateError}
                                        onChange={event => setRegisterBirthDate(event.target.value)}
                                    />
                                    <NumberFormat
                                        type="text"
                                        name="registerCEP"
                                        id="registerCEP"
                                        placeholder="CEP"
                                        label="CEP: "
                                        autoComplete="off"
                                        format="#####-###"
                                        customInput={CustomInput}
                                        value={registerCEP}
                                        hint={registerCEPHint}
                                        error={registerCEPError}
                                        onChange={event => setRegisterCEP(event.target.value)}
                                    />
                                    <NumberFormat
                                        type="text"
                                        name="registerContact"
                                        id="registerContact"
                                        placeholder="Contato"
                                        label="Contato: "
                                        autoComplete="tel-national"
                                        format="(##) #####-####"
                                        customInput={CustomInput}
                                        value={registerContact}
                                        hint={registerContactHint}
                                        error={registerContactError}
                                        onChange={event => setRegisterContact(event.target.value)}
                                    />
                                    <CustomRadio
                                        label="Masculino"
                                        id="male"
                                        name="registerGender"
                                        value="male"
                                        onChange={() => setRegisterGender('male')}
                                    />
                                    <CustomRadio
                                        label="Feminino"
                                        id="famale"
                                        name="registerGender"
                                        value="famale"
                                        onChange={() => setRegisterGender('famale')}
                                    />
                                </form>
                            )}
                        </CSSTransition>
                    </TransitionGroup>
                } // body
                footer={
                    <TransitionGroup className="login-modal-footer">
                        <CSSTransition
                            key={modalPage}
                            timeout={500}
                            classNames="login-modal-footer"
                        >
                            {modalPage ? (
                                <div className="login-footer-content">
                                    <CustomButton value="Entrar" onClick={() => authenticate()}/>
                                    <p>Ainda não tem uma conta?</p>
                                    <CustomButton value="Cadastrar" onClick={() => setModalPage(!modalPage)}/>
                                </div>
                            ) : (
                                <div className="login-footer-content">
                                    <CustomButton value="Cadastrar" onClick={() => register()}/>
                                    <p>Já tem uma conta?</p>
                                    <CustomButton value="Entrar" onClick={() => setModalPage(!modalPage)}/>
                                </div>
                            )}
                        </CSSTransition>
                    </TransitionGroup>
                } // footer
            />
            <Toast
                isShowing={isShowingToast}
                timeout={5000}
                message={toastMessage}
                type={toastType}
                hide={toggleToast}
            />
        </>
    ) // return
} // LoginModal

export default LoginModal