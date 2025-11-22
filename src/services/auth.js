import api from './api'

export const authService = {
  register (data) {
    return api.post('/auth/register', data)
  },

  login (email, password) {
    return api.post('/auth/login', {
      email,
      password
    })
  },

  logout () {
    return api.post('/auth/logout')
  },

  getProfile () {
    return api.get('/auth/me')
  },

  refreshToken () {
    return api.post('/auth/refresh')
  },

  setToken (token) {
    localStorage.setItem('token', token)
  },

  getToken () {
    return localStorage.getItem('token')
  },

  removeToken () {
    localStorage.removeItem('token')
  },

  isAuthenticated () {
    return !!this.getToken()
  }

}
