<template lang="">
  <div>
    <div class="v-card__profile" :class="(toggleProfile ? 'v-display-block' : 'v-display-none')">
      <ul class="v-items__profile">
        <li class="v-item__profile">
          <a href="#">Edit profile</a>
        </li>
        <li class="v-item__profile">
          <a href="#">Inbox</a>
        </li>
        <li class="v-item__profile">
          <a href="#">Setting</a>
        </li>
      </ul>
      <div class="v-card__footer">
        <a href="#">
          Logout
        </a>
      </div>
    </div>
  </div>
</template>
<script>
import { storeToRefs } from 'pinia';
import { useLayoutStore } from '~/stores/layout';

export default {
  setup() {
    // Destructuring for state/getter of layout store
    const {
      toggleProfile,
    } = storeToRefs(useLayoutStore());

    return {
      toggleProfile,
    };
  }
}
</script>
<style lang="">
  
</style>