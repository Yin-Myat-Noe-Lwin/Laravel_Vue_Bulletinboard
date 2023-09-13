import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import UserList from '../views/users/UserList.vue'
import UserCreate from '../views/users/UserCreate.vue'
import PostList from '../views/posts/PostList.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue')
    },
    {
      path: '/UserList',
      name: 'UserList',
      component: UserList
    },
    {
      path: '/UserCreate',
      name: 'UserCreate',
      component: UserCreate
    },
    {
      path: '/PostList',
      name: 'PostList',
      component: PostList
    },
  ]
})

export default router
