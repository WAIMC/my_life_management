<template>
  <div>
    <div class="v-container">
      <!-- Header -->
      <div class="v-header">
        <Header />
      </div>
      <!-- Article -->
      <div 
        class="v-article"
        :class="toggleBar ? 'v-close__sidebar v-hidden__sidebar-mobile' : 'v-show__sidebar-mobile'"
      >
        <!-- Sidebar nav -->
        <div class="v-sidebar__nav">
          <div class="v-sidebar__nav-card v-box-shadow">
            <div class="v-header__sidebar">
              <a href="#" class="v-row v-justify-content-center v-align-items-center">
                <img src="/favicon.ico" alt="">
                <span>
                  sidebar header
                </span>
              </a>
            </div>
            <div class="v-body__sidebar">
              <SidebarMenu />
            </div>
          </div>
        </div>
        <!-- Aside content -->
        <div class="v-aside__content">
          <slot />
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { storeToRefs } from 'pinia';
import { useLayoutStore } from '~/stores/layout';
import Header from '~/layouts/header.vue';
import SidebarMenu from '~/components/menu/sidebar.vue';

export default {
  components: {
    Header,
    SidebarMenu,
  },
  setup() {
  // Destructuring for state/getter of layout store
  const {
    toggleBar,
  } = storeToRefs(useLayoutStore());

  return {
    toggleBar,
  };
  }
}
</script>
<style lang="scss">

</style>
