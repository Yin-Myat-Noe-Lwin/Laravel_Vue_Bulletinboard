<template>
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <RouterLink to="/" class="navbar-brand" aria-current="page">Bulletin_Board</RouterLink>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item li-item">
            <RouterLink to="/UserList" class="nav-link" aria-current="page" active-class="active">Users</RouterLink>
          </li>
          <li class="nav-item li-item">
            <RouterLink to="/PostList" class="nav-link" aria-current="page" active-class="active">Posts</RouterLink>
          </li>
        </ul>
        <ul class="navbar-nav">
          <li v-if="!user" class="nav-item li-item"  active-class="active">
            <RouterLink  to="/signup" class="nav-link" aria-current="page" active-class="active">Register</RouterLink>
          </li>
          <li v-if="!user" class="nav-item"  active-class="active">
            <RouterLink to="/login" class="nav-link" aria-current="page" active-class="active">Login</RouterLink>
          </li>
          <li v-if="user" class="nav-item li-item">
            <RouterLink to="/UserCreate" class="nav-link" aria-current="page" active-class="active">Create User</RouterLink>
          </li>
          <li v-if="user" class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              {{ user.name }}   <font-awesome-icon :icon="['fas', 'user-gear']" />
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><RouterLink to="/UserProfile" class="dropdown-item" aria-current="page">Profile</RouterLink></li>
              <li class="dropdown-item" aria-current="page" @click="logout">Logout</li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script setup>

  import { RouterLink } from 'vue-router';
  import axiosInstance from '@/axios.js';
  import { useRouter } from 'vue-router';
  import { useStore } from 'vuex';

  const store = useStore();

  const router = useRouter();

  //get current logged in user
  const user = JSON.parse(localStorage.getItem('user')) || JSON.parse(sessionStorage.getItem('user')) || null;

  async function logout() {
    try{
      const response = await axiosInstance.post('/logout');
      if (response.status === 200) {
          if (localStorage.getItem('user')) {
            localStorage.removeItem('user');
            localStorage.removeItem('token');
          } else if (sessionStorage.getItem('user')) {
            sessionStorage.removeItem('user');
            sessionStorage.removeItem('token');
          }
          store.dispatch('deleteUserData');
          store.dispatch('deletePostData');
          if (sessionStorage.getItem('file')) {
            sessionStorage.removeItem('file');
          }
          router.push('/');
          setTimeout(() => {
            window.location.reload();
          }, 5);
      }
      else {
          console.error('Logout failed:', response.data);
      }
    }catch(error) {
      console.error('Logout failed:', error);
    }
  }

</script>
