<script setup lang="ts">
import ParentLayout from '@vuepress/theme-default/layouts/Layout.vue'
import AnnouncementBanner from "../components/AnnouncementBanner.vue";
import InPageNav from "../components/InPageNav.vue";
</script>

<template>
    <AnnouncementBanner/>
    <InPageNav/>
    <ParentLayout/>
</template>

<style lang="scss">
</style>
