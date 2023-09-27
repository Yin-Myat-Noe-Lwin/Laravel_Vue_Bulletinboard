<script setup>

import { RouterLink } from 'vue-router';
import axiosInstance from '@/axios.js';
import { useRouter } from 'vue-router';


const router = useRouter();

const user = JSON.parse(localStorage.getItem('user'));

async function logout() {
  try{
    const response = await axiosInstance.post('/logout');
    if (response.status === 200) {
          localStorage.removeItem('user');
          localStorage.removeItem('token');
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

<template>
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <RouterLink to="/" class="navbar-brand" aria-current="page">Bulletin_Board</RouterLink>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <RouterLink to="/UserList" class="nav-link" aria-current="page">Users</RouterLink>
          </li>
          <li class="nav-item">
            <RouterLink to="/PostList" class="nav-link" aria-current="page">Posts</RouterLink>
          </li>
        </ul>
        <ul class="navbar-nav">
          <li v-if="!user" class="nav-item">
            <RouterLink  to="/register" class="nav-link" aria-current="page">Register</RouterLink>
          </li>
          <li v-if="!user" class="nav-item">
            <RouterLink to="/login" class="nav-link" aria-current="page">Login</RouterLink>
          </li>
          <li v-if="user" class="nav-item">
            <RouterLink to="/UserCreate" class="nav-link" aria-current="page">Create User</RouterLink>
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


