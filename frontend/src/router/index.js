import { createRouter, createWebHistory } from 'vue-router'
import axiosInstance from '@/axios.js';
import NotFound from '../views/common/NotFound.vue'
import HomeView from '../views/HomeView.vue'
import UserList from '../views/users/UserList.vue'
import UserCreate from '../views/users/UserCreate.vue'
import UserCreateConfirm from '../views/users/UserCreateConfirm.vue'
import UserProfile from '../views/users/UserProfile.vue'
import UserEdit from '../views/users/UserEdit.vue'
import PostList from '../views/posts/PostList.vue'
import PostCreate from '../views/posts/PostCreate.vue'
import PostCreateConfirm from '../views/posts/PostCreateConfirm.vue'
import PostEdit from '../views/posts/PostEdit.vue'
import PostEditConfirm from '../views/posts/PostEditConfirm.vue'
import ForgotPassword from '../views/auth/ForgotPassword.vue'
import ChangePassword from '../views/auth/ChangePassword.vue'
import ResetPassword from '../views/auth/ResetPassword.vue'
import AppRegister from '../views/auth/AppRegister.vue'
import AppLogin from '../views/auth/AppLogin.vue'
import UploadCSV from '../views/common/UploadCSV.vue'

const user = JSON.parse(localStorage.getItem('user'))

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/:pathMatch(.*)*',
      name: 'NotFound',
      component: NotFound,
    },
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/UserList',
      name: 'UserList',
      component: UserList
    },
    {
      path: '/UserCreate',
      name: 'UserCreate',
      component: UserCreate,
      meta: { requiresAuth: true },
    },
    {
      path: '/UserCreateConfirm',
      name: 'UserCreateConfirm',
      component: UserCreateConfirm,
      meta: { requiresAuth: true }
    },
    {
      path: '/UserProfile',
      name: 'UserProfile',
      component: UserProfile,
      meta: { requiresAuth: true }
    },
    {
      path: '/UserEdit',
      name: 'UserEdit',
      component: UserEdit,
      meta: { requiresAuth: true }
    },
    {
      path: '/PostList',
      name: 'PostList',
      component: PostList
    },
    {
      path: '/PostCreate',
      name: 'PostCreate',
      component: PostCreate,
      meta: { requiresAuth: true }
    },
    {
      path: '/PostCreateConfirm',
      name: 'PostCreateConfirm',
      component: PostCreateConfirm,
      meta: { requiresAuth: true }
    },
    {
      path: '/PostEdit/:postID',
      name: 'PostEdit',
      component: PostEdit,
      meta: { requiresAuth: true }
    },
    {
      path: '/PostEditConfirm/:postID',
      name: 'PostEditConfirm',
      component: PostEditConfirm,
      meta: { requiresAuth: true }
    },
    {
      path: '/ChangePassword',
      name: 'ChangePassword',
      component: ChangePassword,
      meta: { requiresAuth: true }
    },
    {
      path: '/ForgotPassword',
      name: 'ForgotPassword',
      component: ForgotPassword,
      beforeEnter: requireAuth
    },
    {
      path: '/resetPassword/:userId-:token',
      name: 'resetPassword',
      component: ResetPassword,
      beforeEnter: requireAuth
    },
    {
      path: '/register',
      name: 'Register',
      component: AppRegister
    },
    {
      path: '/login',
      name: 'login',
      component: AppLogin,
      beforeEnter: requireAuth
    },
    {
      path: '/UploadCSV',
      name: 'UploadCSV',
      component: UploadCSV
    }
  ]
})

router.beforeEach(async (to, from, next) => {
  if (to.meta.requiresAuth) {
    try {
      const response = await axiosInstance.get(`/users/${user.id}`);
      if (response.status === 200) {
        next();
      } else {
        next({ name: 'login' });
      }
    } catch (error) {
      console.error('Authentication Error:', error);
      next({ name: 'login' });
    }
  } else {
    next();
  }
});

  function isLoggedIn() {
    const user = JSON.parse(localStorage.getItem('user'));
    return user !== null;
  }

  function requireAuth(to, from, next) {
    if (!isLoggedIn()) {
      next();
    } else {
      next({ name: 'home' });
    }
  }

export default router
