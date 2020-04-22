import React, { useState, useEffect, useRef } from 'react'
import { Link } from 'react-router-dom'
import decode from 'jwt-decode'
import './index.css'

import Modal from '../../Modal'
import Toast from '../../Toast'
import CustomButton from '../../CustomButton'
import LoginModal from '../../LoginModal'
import { isAuthenticated, logout } from '../../../services/auth'
import BottomMenu from "../../BottomMenu";

const Home = () => {

    const divDraggable = useRef(null)
    const [ x, setX ] = useState(0)
    const [ y, setY ] = useState(0)

    const [ isShowing, setIsShowing ] = useState(false)
    const [ isShowing2, setIsShowing2 ] = useState(false)
    const [ isShowing3, setIsShowing3 ] = useState(false)
    const [ isAuthenticatedState, setIsAuthenticatedState ] = useState(isAuthenticated())

    useEffect(() => {
        isAuthenticated() ? setIsAuthenticatedState(true) : setIsAuthenticatedState(false)
    }, [ isShowing2 ])

    const handleLogout = () => {
        logout()
        setIsAuthenticatedState(false)
    }

    function toggle() {
        setIsShowing(!isShowing)
    }

    function toggle2() {
         setIsShowing2(!isShowing2)
    }

    function toggle3() {
        setIsShowing3(!isShowing3)
    }

    const handleTouchStart = event => {
        setX(event.touches[0].pageX - x)
        setY(event.touches[0].pageY - y)
    }

    const handleTouchMove = event => {
        divDraggable.current.style.left = `${(event.touches[0].pageX - x)}px`
        divDraggable.current.style.top = `${(event.touches[0].pageY - y)}px`
    }

    const handleTouchEnd = event => {
        setX(event.changedTouches[0].pageX - x)
        setY(event.changedTouches[0].pageY - y)
    }

    return (
        <>
            <div
                onTouchStart={handleTouchStart}
                onTouchMove={handleTouchMove}
                onTouchEnd={handleTouchEnd}
                ref={divDraggable}
                style={{position: 'absolute', backgroundColor: 'lightgray', padding: '10px', textAlign: 'center'}}
            >
                <h1>Home</h1>
                <Link to="/">Voltar</Link> | <Link to="/debug">Debug</Link>
                <br/><br/>
                <Link to="/app">App</Link> |&nbsp;
                <CustomButton onClick={toggle} value="Test Modal" /> |&nbsp;
                {!isAuthenticatedState ? <><CustomButton onClick={toggle2} value="Login Modal" /> |&nbsp;</>: null}
                <CustomButton onClick={toggle3} value="Test Toast" />
                {isAuthenticatedState ? <> |&nbsp;<CustomButton onClick={handleLogout} value="Logout" /></>: null}
            </div>
            <Modal
                isShowing={isShowing}
                hide={() => toggle()}
                header="Teste"
                body="Conteúdo"
                footer="Footer"
            />
            <LoginModal
                isShowing={isShowing2}
                hide={() => toggle2()}
            />
            <Toast
                isShowing={isShowing3}
                hide={() => toggle3()}
                timeout={2000}
                type="info"
                message="Teste"
            />
            <BottomMenu/>
        </>
    ) // return
} // Home

export default Home