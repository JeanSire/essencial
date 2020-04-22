import React, { useRef, useEffect } from 'react'

const CustomInput = ({ label, placeholder, hint, type, value, id, name, error, autoComplete, onChange, onBlur, onFocus }) => {

    const inputRef = useRef(null)

    useEffect(() => {
        if (error) {
            inputRef.current.classList.add('bg-red-l-50')
            inputRef.current.focus()
        } else {
            inputRef.current.classList.remove('bg-red-l-50')
        } // else
    }, [error]) // useEffect

    return (
        <>
            <label htmlFor={id} className="col-12 d-block">{label}</label>
            <input
                className="col-12 d-block"
                type={type}
                placeholder={placeholder}
                value={value}
                id={id}
                name={name}
                ref={inputRef}
                onChange={onChange}
                onBlur={onBlur}
                onFocus={onFocus}
                autoComplete={autoComplete}
            />
            <br/>
            <small className="col-12 d-block">{hint}</small>
        </>
    ) // return
} // Input

export default CustomInput