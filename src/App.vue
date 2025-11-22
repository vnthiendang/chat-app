<template>
  <div id="app">
    <header class="navbar">
      <div class="navbar-container">
        <div class="navbar-brand">
          <img src="./assets/logo.png" alt="Logo" class="logo">
          <span>Chat App</span>
        </div>
        <nav class="navbar-menu">
          <template v-if="!isAuthenticated">
            <router-link to="/login" class="btn btn-login">Sign In</router-link>
            <router-link to="/register" class="btn btn-register">Register</router-link>
          </template>
          <template v-else>
            <span class="user-name">{{ currentUser && currentUser.name }}</span>
            <router-link to="/chat" class="btn btn-chat">Chat</router-link>
            <router-link to="/profile" class="btn btn-profile">Profile</router-link>
            <button @click="handleLogout" class="btn btn-logout">Sign Out</button>
          </template>
        </nav>
      </div>
    </header>

    <main class="container">
      <router-view />
    </main>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  name: 'App',
  computed: {
    ...mapGetters(['isAuthenticated', 'currentUser'])
  },
  methods: {
    ...mapActions(['logout', 'fetchProfile']),
    async handleLogout () {
      await this.logout()
      this.$router.push('/login')
    }
  },
  async mounted () {
    if (this.isAuthenticated) {
      try {
        await this.fetchProfile()
      } catch (error) {
        console.error('Failed to fetch profile:', error)
        this.logout()
      }
    }
  }
}
</script>

<style scoped>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
}

.navbar {
  background-color: #ffffff;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  position: sticky;
  top: 0;
  z-index: 100;
}

.navbar-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 1rem 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.navbar-brand {
  display: flex;
  align-items: center;
  gap: 1rem;
  font-size: 1.5rem;
  font-weight: bold;
  color: #2c3e50;
  text-decoration: none;
}

.logo {
  height: 40px;
  width: auto;
}

.navbar-menu {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.btn {
  padding: 0.5rem 1rem;
  border-radius: 4px;
  text-decoration: none;
  border: none;
  cursor: pointer;
  font-size: 1rem;
  transition: all 0.3s ease;
}

.btn-login {
  color: #42b983;
  border: 2px solid #42b983;
  background-color: transparent;
}

.btn-login:hover {
  background-color: #42b983;
  color: white;
}

.btn-register {
  color: white;
  background-color: #42b983;
}

.btn-register:hover {
  background-color: #369970;
}

.btn-profile {
  color: #42b983;
  border: 2px solid #42b983;
  background-color: transparent;
}

.btn-profile:hover {
  background-color: #42b983;
  color: white;
}

.btn-logout {
  color: white;
  background-color: #e74c3c;
}

.btn-logout:hover {
  background-color: #c0392b;
}

.user-name {
  font-weight: 500;
  color: #2c3e50;
  margin-right: 1rem;
}

.container {
  max-width: 1200px;
  margin: 2rem auto;
  padding: 0 2rem;
  min-height: calc(100vh - 200px);
}
.btn-chat {
  color: #42b983;
  border: 2px solid #42b983;
  background-color: transparent;
}

.btn-chat:hover {
  background-color: #42b983;
  color: white;
}
</style>
