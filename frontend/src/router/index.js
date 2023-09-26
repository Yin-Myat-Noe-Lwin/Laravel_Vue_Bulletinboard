import { createRouter, createWebHistory } from 'vue-router'
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
import ChangePassword from '../views/auth/ChangePassword.vue'
import ResetPassword from '../views/auth/ResetPassword.vue'
import AppRegister from '../views/auth/AppRegister.vue'
import AppLogin from '../views/auth/AppLogin.vue'
import UploadCSV from '../views/common/UploadCSV.vue'

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
      component: UserCreate
    },
    {
      path: '/UserCreateConfirm',
      name: 'UserCreateConfirm',
      component: UserCreateConfirm
    },
    {
      path: '/UserProfile',
      name: 'UserProfile',
      component: UserProfile
    },
    {
      path: '/UserEdit',
      name: 'UserEdit',
      component: UserEdit
    },
    {
      path: '/PostList',
      name: 'PostList',
      component: PostList
    },
    {
      path: '/PostCreate',
      name: 'PostCreate',
      component: PostCreate
    },
    {
      path: '/PostCreateConfirm',
      name: 'PostCreateConfirm',
      component: PostCreateConfirm
    },
    {
      path: '/PostEdit/:postID',
      name: 'PostEdit',
      component: PostEdit
    },
    {
      path: '/PostEditConfirm/:postID',
      name: 'PostEditConfirm',
      component: PostEditConfirm
    },
    {
      path: '/ChangePassword',
      name: 'ChangePassword',
      component: ChangePassword
    },
    {
      path: '/ResetPassword',
      name: 'ResetPassword',
      component: ResetPassword
    },
    {
      path: '/ResetPassword',
      name: 'ResetPassword',
      component: ResetPassword
    },
    {
      path: '/register',
      name: 'Register',
      component: AppRegister
    },
    {
      path: '/login',
      name: 'login',
      component: AppLogin
    },
    {
      path: '/UploadCSV',
      name: 'UploadCSV',
      component: UploadCSV
    }
  ]
})

export default router
