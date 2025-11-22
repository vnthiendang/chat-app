<template>
  <div class="home-container">
    <div class="welcome-card" v-if="isAuthenticated">
      <h1>Welcome, {{ currentUser && currentUser.name }}!</h1>
      <router-link to="/profile" class="btn-profile">View Profile</router-link>
    </div>

    <div class="welcome-card" v-else>
      <h1>Welcome to real-time Chat App</h1>
      <p>Please signin or register to continue.</p>
      <div class="btn-group">
        <router-link to="/login" class="btn btn-login">Sign In</router-link>
        <router-link to="/register" class="btn btn-register">Register</router-link>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'Home',
  computed: {
    ...mapGetters(['isAuthenticated', 'currentUser'])
  }
}
</script>

<style scoped>
.home-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 50vh;
}

.welcome-card {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  text-align: center;
  max-width: 600px;
}

h1 {
  color: #2c3e50;
  margin-bottom: 1rem;
}

p {
  color: #7f8c8d;
  margin-bottom: 2rem;
}

.btn-group {
  display: flex;
  gap: 1rem;
  justify-content: center;
}

.btn {
  padding: 0.75rem 2rem;
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
  display: inline-block;
  padding: 0.75rem 2rem;
  background-color: #42b983;
  color: white;
  text-decoration: none;
  border-radius: 4px;
  transition: background-color 0.3s ease;
}

.btn-profile:hover {
  background-color: #369970;
}
</style>
