<script setup>
import { RouterView } from 'vue-router';
import AppHeader from '@/components/common/AppHeader.vue';
import AppFooter from '@/components/common/AppFooter.vue';
</script>

<template>
  <AppHeader/>
  <RouterView/>
  <AppFooter/>
</template>
