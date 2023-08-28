<template lang="">
  <div>
    <div class="v-row v-justify-content-between v-align-items-center v-header__swap">
      <div class="v-avatar__header">
        <a href="#" class="v-row v-align-items-center v-logo__header">
          <img src="/favicon.ico" alt="">
          <span>
            sidebar header
          </span>
        </a>
        <button @click="onToggleBar">
          <i
            class="fa-solid fa-bars v-icon__bars"
            :class="toggleBar ? 'v-display-block' : 'v-display-none'"
          ></i>
          <i
            class="fa-solid fa-bars-staggered v-icon__bars"
            :class="!toggleBar ? 'v-display-block' : 'v-display-none'"
          ></i>
        </button>
      </div>
      <div class="v-items__header">
        <div class="v-row v-justify-content-between v-align-items-center v-item__header">
          <div class="v-item__search">
            <input type="text" id="search__header" placeholder="search...">
            <label for="search__header">
              <i class="fa-solid fa-magnifying-glass v-icon-search__header"></i>
            </label>
          </div>
          <div class="v-item__notification">
            <button class="" @click="onToggleNotification">
              <i class="fa-solid fa-bell v-icon-ntf__header"></i>
              <span class="v-number-ntf__header">10</span>
            </button>
            <div class="v-notification__content">
              <CardCustom />
            </div>
          </div>
          <div class="v-content__profile">
            <a 
              href="#" 
              class=" v-row v-justify-center v-align-items-center"
              @click="onToggleProfile"
            >
              <img src="~/assets/image/user-13.jpg" alt="image">
              <span>VinhDV</span>
            </a>
            <div class="v-profile__content">
              <CardProfile />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { storeToRefs } from 'pinia';
import { useLayoutStore } from '~/stores/layout';
import CardCustom from "~/components/card/custom.vue";
import CardProfile from "~/components/card/profile.vue";

export default {
  components: {
    CardCustom,
    CardProfile,
  },
  setup() {
    // Destructuring for state/getter of layout store
    const {
      toggleBar,
    } = storeToRefs(useLayoutStore());

    // Destructuring for action of store
    const { 
      setToggleBar,
      setToggleNotification,
      setToggleProfile,
    } = useLayoutStore();

    function onToggleNotification() {
      setToggleNotification();
    }

    function onToggleProfile() {
      setToggleProfile();
    }

    function onToggleBar() {
      setToggleBar();
    }

    return {
      toggleBar,
      onToggleBar,
      onToggleNotification,
      onToggleProfile,
    };
  }
}
</script>
<style lang="">
  
</style>
