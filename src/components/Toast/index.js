import React, { useState } from 'react'
import { CSSTransition } from 'react-transition-group'
import './index.css'

const Toast = ({ isShowing, hide, type, timeout, message }) => {

    const [ timer, setTimer ] = useState(false)

    return (
        <CSSTransition
            in={isShowing}
            timeout={250}
            classNames="toast"
            onEnter={() => {
                setTimeout(hide, timeout)
                setTimer(!timer)
            }}
            onExit={() => setTimer(!timer)}
            unmountOnExit
        >
            <div className="toast">
                <div className="toast-wraper">
                    <i className={`fas fa-${type}`}/>&nbsp;&nbsp;
                    <p>{message}</p>
                    <button className="toast-close-button" onClick={hide}>&times;</button>
                </div>
                <CSSTransition in={timer} timeout={timeout} classNames="toast-timer">
                    <div className="toast-timer"  style={{
                        transitionProperty: 'width',
                        transitionDuration: `${timeout}ms`,
                        transitionTimingFunction: 'linear'
                    }}/>
                </CSSTransition>
            </div>
        </CSSTransition>
    )
}

export default Toast