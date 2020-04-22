import React from 'react';
import { Link } from 'react-router-dom'

const Error404 = () => {
    return(
        <>
            <h1>404</h1>
            <h2>Ooops!</h2>
            <h2>Esta pánina não existe!</h2>
            <h3>Parece que você se perdeu<br/>em nosso portal...</h3>
            <Link to="/home">Votar ao início <i className="fas fa-chevron-right"></i></Link>
        </>
    )
}

export default Error404;