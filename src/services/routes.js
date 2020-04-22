import React from 'react'
import { isAuthenticated } from './auth'
import { BrowserRouter, Switch, Route, Redirect } from 'react-router-dom'

import Home from '../components/view/Home'
import Entry from '../components/view/Entry'
import Debug from '../components/view/Debug'
import Error404 from "../components/view/Error404";
import { CSSTransition, TransitionGroup } from "react-transition-group";
import '../css/pageTransition.css'

const PrivateRoute = ({ component: Component, ...rest }) => (
    <Route {...rest} render={props => (
        isAuthenticated() ? (
            <Component {...props} />
        ) : (
            <Redirect to={{ pathName: '/', state: { from: props.location } }}/>
        )
    )}/>
) // PrivateRoute

const Routes = () => (
    <BrowserRouter>
        <Route render={location => (
            <TransitionGroup className="wrapContainer">
                <CSSTransition
                    key={location.location.pathname}
                    timeout={400}
                    classNames="pageTransition"
                >
                    <div className="pageContent">
                        <Switch location={location.location}>
                            <Route path="/login" component={() => {
                                window.location.href = 'http://essencialavida.com/login'
                                return null
                            }}/>
                            {/*<PrivateRoute path="/app" component={() => <h1>You're on app</h1>} />*/}
                            {/*<Route path="/debug" component={() => <Debug />} />*/}
                            {}/*<Route path="/home" component={() => <Home />} />*/}
                            <Route exact path="/" component={() => <Entry />} />
                            <Route component={() => <Error404 />}/>
                        </Switch>
                    </div>
                </CSSTransition>
            </TransitionGroup>
        )}/>
    </BrowserRouter>
) // Routes

export default Routes