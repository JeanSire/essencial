import React from 'react'

const CustomButton = ({ value, btnType, className, onClick, disabled }) => <button onClick={onClick} disabled={disabled} className={className}>{value}</button>

export default CustomButton