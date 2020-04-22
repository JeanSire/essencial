import React, { useRef, useEffect } from 'react'

const CustomRadio = ({ checked, disabled, name, id, value, label, error, onChange }) => {

    const inputRef = useRef(null)

    useEffect(() => {
        if (error) {
            inputRef.current.style.outline = '2px solid red'
        } else {
            inputRef.current.style.outline = 'none'
        } // else
    }, [error]) // useEffect

    return(
        <>
            <input
                type="radio"
                checked={checked}
                disabled={disabled}
                name={name}
                id={id}
                value={value}
                ref={inputRef}
                onChange={onChange}
            />
            <label htmlFor={id}>{label}</label>
        </>
    )
}

export default CustomRadio