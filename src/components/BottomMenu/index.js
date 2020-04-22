import React, { useState, useEffect, useRef } from 'react'
import './index.css'

const BottomMenu = () => {

    // Valor da altura da tela em pixels
    const viewHeight = Math.max(document.documentElement.clientHeight, window.innerHeight || 0)

    // O valor inicial é de 90% da altura total da tela
    const [ y, setY ] = useState(viewHeight * 0.9)

    const menu = useRef(null)

    // Ao tocar no menu
    const handleTouchStart = event => {
        setY(event.touches[0].clientY - y)
    } // handleTouchStart

    // Ao arrastar
    const handleTouchMove = event => {
        if (
            (event.touches[0].clientY - y) < (viewHeight - (viewHeight * 0.1)) &&
            (event.touches[0].clientY - y) > (viewHeight - (viewHeight * 0.9))
        ) {
            menu.current.style.top = `${(event.touches[0].clientY - y)}px`
        } // if
    } // handleTouchMove

    // Soltar o toque
    const handleTouchEnd = event => {
        const change = event.changedTouches[0].clientY - y

        if (change > (viewHeight * 0.7)) {
            menu.current.style.top = '90%'
            setY(viewHeight * 0.9)
        } else {
            menu.current.style.top = '10%'
            setY(viewHeight * 0.1)
        } // else
    } // handleTouchEnd

    return (
        <div className="bottom-menu"
             onTouchStart={handleTouchStart}
             onTouchMove={handleTouchMove}
             onTouchEnd={handleTouchEnd}
             ref={menu}
        >
            <div className="gesture-button"/>
        </div>
    ) // return
} // BottomMenu

export default BottomMenu