import React, { useState, useEffect } from 'react'
import { Link } from 'react-router-dom'
import { CSSTransition } from 'react-transition-group'
import './index.css'
import CustomButton from "../../CustomButton";
import { ReactComponent as Essencial } from './design/svg/essencial.svg'
import { ReactComponent as LogoEssencial } from './design/svg/logo_essencial.svg'
import { ReactComponent as Login } from './design/svg/login.svg'

const Entry = () => {

    const [ animateTitle, setAnimateTitle ] = useState(false)
    const [ transition, setTransition ] = useState(false)
    const [ buttonPlayAnimation, setButtonPlayAnimation ] = useState(false)

    function transitionToggle() {
        setTransition(!transition)
    } // transitionToggle

    function toggleButtonPlayAnimation() {
        setButtonPlayAnimation(!buttonPlayAnimation)
    } // buttonPlayAnimation


    return(
        <>
            <div className={`color3 ${transition ? ' opacity20' : ''}`} />
            <CSSTransition in={ transition } timeout={700} classNames="logoTransition">
                <div className="logoTransition">
                    <Essencial className="pos"/>
                    <LogoEssencial className="pos2" />
                </div>
            </CSSTransition>
            <CSSTransition in={ transition } timeout={700} classNames="shapeLeft">
                <div className="color" />
            </CSSTransition>
            <CSSTransition in={ transition } timeout={700} classNames="shapeRight">
                <div className="color2" />
            </CSSTransition>
            <CSSTransition in={ transition } timeout={700} classNames="continueButton">
                <div onClick={() => transitionToggle()}>
                    <Login className="pos3"/>
                </div>
            </CSSTransition>
            <CSSTransition in={ transition } timeout={300} classNames="backgroundOverlay">
                <div className="backgroundOverlay"/>
            </CSSTransition>
            <CSSTransition in={transition} timeout={700} classNames="contentAnimation" onEnter={() => toggleButtonPlayAnimation()} onExit={() => toggleButtonPlayAnimation()}>
                <div className="contentAnimation">
                    <p>
                        A Essencial cria produtos de prevenção e promoção à saúde pensados para a qualidade de vida. São
                        soluções que miram o futuro, com produtos e ferramentas para se viver mais e melhor. Nossas premissas
                        se baseiam no tríduo perfeito para uma vida com qualidade: comer bem, dormir bem e praticar atividade
                        física. É assim que trabalhamos para prevenir doenças, promover bem-estar e longevidade. Esse é o
                        propósito da nossa marca e de cada inovação lançada para ser Essencial.
                        <br/><br/>
                        Este portal está em construção, mas por enquanto, nos siga no instagram e fique por dentro das novidades:
                        <br/><br/>
                        <span style={{ color: '#f21b54', borderBottom: '3px solid #f21b54' }}>
                            <i className="fab fa-instagram"/>
                            &nbsp;&nbsp;&nbsp;
                            <a
                                href="https://www.instagram.com/loginpalmilhas/"
                                style={{ color: '#f21b54', textDecoration: 'none' }}
                            >
                                @loginpalmilhas &rarr;
                            </a>
                        </span>
                    </p>
                    <Link to="/login">
                        <CSSTransition in={buttonPlayAnimation} timeout={1300} classNames="startButtonAnimation">
                            <CustomButton className="startButtonAnimation" value="Começar &rarr;"/>
                        </CSSTransition>
                    </Link>
                    <p style={{marginTop: '70px'}}>
                        Ou entre em contato:
                        <br/>
                        <i className="far fa-envelope"/>&nbsp;&nbsp;&nbsp;contato@essencialavida.com
                    </p>
                </div>
            </CSSTransition>
        </>
    ) //return
} // Entry

export default Entry