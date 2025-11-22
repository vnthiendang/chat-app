<template>
  <div class="login-container">
    <div class="login-card">
      <h1>Sign In</h1>
      <form @submit.prevent="handleLogin">
        <div class="form-group">
          <label for="email">Email:</label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            required
            placeholder="Enter email"
          >
        </div>

        <div class="form-group">
          <label for="password">Password:</label>
          <input
            id="password"
            v-model="form.password"
            type="password"
            required
            placeholder="Enter password"
          >
        </div>

        <button type="submit" :disabled="loading" class="btn-submit">
          {{ loading ? 'Processing...' : 'Sign In' }}
        </button>

        <p class="error-message" v-if="errorMessage">{{ errorMessage }}</p>
      </form>

      <p class="redirect-text">
        Did not have an account?
        <router-link to="/register">Register here</router-link>
      </p>
    </div>
  </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  name: 'Login',
  data () {
    return {
      form: {
        email: '',
        password: ''
      },
      loading: false,
      errorMessage: ''
    }
  },
  methods: {
    ...mapActions(['login']),
    async handleLogin () {
      this.loading = true
      this.errorMessage = ''

      try {
        await this.login({
          email: this.form.email,
          password: this.form.password
        })
        this.$router.push('/')
      } catch (error) {
        this.errorMessage = error.response.data.message || 'Sign In failed. Pls try again.'
      } finally {
        this.loading = false
      }
    }
  }
}
</script>

<style scoped>
.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 60vh;
}

.login-card {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
}

h1 {
  text-align: center;
  margin-bottom: 2rem;
  color: #2c3e50;
}

.form-group {
  margin-bottom: 1.5rem;
}

label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: #2c3e50;
}

input {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
}

input:focus {
  outline: none;
  border-color: #42b983;
  box-shadow: 0 0 0 3px rgba(66, 185, 131, 0.1);
}

.btn-submit {
  width: 100%;
  padding: 0.75rem;
  background-color: #42b983;
  color: white;
  border: none;
  border-radius: 4px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.btn-submit:hover:not(:disabled) {
  background-color: #369970;
}

.btn-submit:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.error-message {
  color: #e74c3c;
  text-align: center;
  margin-top: 1rem;
  font-size: 0.9rem;
}

.redirect-text {
  text-align: center;
  margin-top: 1.5rem;
  color: #7f8c8d;
}

.redirect-text a {
  color: #42b983;
  text-decoration: none;
  font-weight: 500;
}

.redirect-text a:hover {
  text-decoration: underline;
}
</style>
