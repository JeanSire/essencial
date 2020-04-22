import axios from 'axios'
import { getToken } from './auth'

const api = axios.create({ baseURL: 'http://10.112.20.49/essencialavida/essencial/src/api/' })

api.interceptors.request.use(async config => {
    const token = getToken()

    if (token) {
        config.headers.Authorization = `Bearer ${token}`
    }

    return config
})

export default api