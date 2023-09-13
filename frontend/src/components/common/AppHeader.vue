<script setup>
import { RouterLink } from 'vue-router';
</script>
<template>
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Bulletin_Board</a>
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
          <li class="nav-item">
            <RouterLink to="/UserCreate" class="nav-link" aria-current="page">Create User</RouterLink>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Leona   <font-awesome-icon :icon="['fas', 'user-gear']" />
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><RouterLink to="#" class="dropdown-item" aria-current="page">Profile</RouterLink></li>
              <li><RouterLink to="#" class="dropdown-item" aria-current="page">Logout</RouterLink></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>


