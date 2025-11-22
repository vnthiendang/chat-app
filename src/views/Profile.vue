<template>
  <div class="profile-container">
    <div class="profile-card" v-if="currentUser">
      <h1>Your Profile</h1>

      <div class="profile-section">
        <h2>Personal Information</h2>
        <div class="info-group">
          <label>Name:</label>
          <p>{{ currentUser.name }}</p>
        </div>
        <div class="info-group">
          <label>User Name:</label>
          <p>{{ currentUser.username }}</p>
        </div>
        <div class="info-group">
          <label>Email:</label>
          <p>{{ currentUser.email }}</p>
        </div>
        <div class="info-group">
          <label>Verify email:</label>
          <p>{{ currentUser.email_verified_at ? 'Verified' : 'Unverified' }}</p>
        </div>
        <div class="info-group">
          <label>Created date:</label>
          <p>{{ formatDate(currentUser.created_at) }}</p>
        </div>
      </div>

      <div class="profile-section" v-if="currentUser.posts && currentUser.posts.length">
        <h2>Chat ({{ currentUser.posts.length }})</h2>
        <div class="posts-list">
          <div class="post-item" v-for="post in currentUser.posts" :key="post.id">
            <div v-if="post.image" class="post-image">
              <img :src="post.image" :alt="post.caption">
            </div>
            <div class="post-content">
              <p class="post-caption">{{ post.caption }}</p>
              <span class="post-date">{{ formatDate(post.created_at) }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="profile-section" v-else>
        <p class="no-posts">Did not own any posts</p>
      </div>
    </div>

    <div class="loading" v-else>
      Loading data...
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  name: 'Profile',
  computed: {
    ...mapGetters(['currentUser'])
  },
  methods: {
    ...mapActions(['fetchProfile']),
    formatDate (dateString) {
      return new Date(dateString).toLocaleDateString('vi-VN')
    }
  },
  async mounted () {
    try {
      await this.fetchProfile()
    } catch (error) {
      console.error('Failed to fetch profile:', error)
      this.$router.push('/login')
    }
  }
}
</script>

<style scoped>
.profile-container {
  display: flex;
  justify-content: center;
  padding: 2rem 0;
}

.profile-card {
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 600px;
  padding: 2rem;
}

h1 {
  text-align: center;
  color: #2c3e50;
  margin-bottom: 2rem;
  border-bottom: 2px solid #42b983;
  padding-bottom: 1rem;
}

h2 {
  color: #2c3e50;
  margin-top: 2rem;
  margin-bottom: 1rem;
  font-size: 1.2rem;
}

.profile-section {
  margin-bottom: 2rem;
}

.info-group {
  display: flex;
  padding: 0.75rem 0;
  border-bottom: 1px solid #ecf0f1;
}

.info-group label {
  font-weight: 600;
  color: #7f8c8d;
  min-width: 150px;
}

.info-group p {
  color: #2c3e50;
  margin: 0;
}

.posts-list {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.post-item {
  border: 1px solid #ecf0f1;
  border-radius: 8px;
  overflow: hidden;
  padding: 1rem;
}

.post-image {
  margin-bottom: 1rem;
}

.post-image img {
  width: 100%;
  height: auto;
  border-radius: 4px;
  max-height: 300px;
  object-fit: cover;
}

.post-content {
  display: flex;
  flex-direction: column;
}

.post-caption {
  color: #2c3e50;
  margin: 0 0 0.5rem 0;
  line-height: 1.5;
}

.post-date {
  color: #95a5a6;
  font-size: 0.85rem;
}

.no-posts {
  text-align: center;
  color: #7f8c8d;
  padding: 2rem 0;
}

.loading {
  text-align: center;
  padding: 2rem;
  color: #7f8c8d;
}
</style>
