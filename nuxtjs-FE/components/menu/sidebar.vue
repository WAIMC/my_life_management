<template lang="">
  <div>
    <ul class="v-nav">
      <li class="v-nav-item" v-for="(menu, indx) in menus" :key="indx">
        <a
          href="#"
          class="v-row v-align-items-center v-justify-content-between v-nav-link"
          @click="toggleMenuSidebars(menu.id)"
          v-if="menu.items"
        >
          <div class="v-row v-align-items-center v-justify-content-start">
            <div class="v-nav-link__icon">
              <i class="fa-solid" :class="menu.icon"></i>
            </div>
            <div class="v-nav-link__text">
              <span class="v-nav-title">{{ menu.label }}</span>
            </div>
          </div>
          <div class="v-nav-link__caret" v-if="menu.items">
            <i v-if="!menu.isShow" class="fa-solid fa-sort-down"></i>
            <i v-if="menu.isShow" class="fa-solid fa-sort-up"></i>
          </div>
        </a>
        <nuxt-link 
          :to="menu.route"
          class="v-row v-align-items-center v-justify-content-between v-nav-link"
          v-if="!menu.items"
        >
          <div class="v-row v-align-items-center v-justify-content-start">
            <div class="v-nav-link__icon">
              <i class="fa-solid" :class="menu.icon"></i>
            </div>
            <div class="v-nav-link__text">
              <span class="v-nav-title">{{ menu.label }}</span>
            </div>
          </div>
          <div class="v-nav-link__caret" v-if="menu.items">
            <i v-if="!menu.isShow" class="fa-solid fa-sort-down"></i>
            <i v-if="menu.isShow" class="fa-solid fa-sort-up"></i>
          </div>
        </nuxt-link>
        <ul
          class="v-nav__child" 
          v-if="menu.items"
          :class="menu?.isShow ? 'v-show__nav-child' : ''"
        >
          <li 
            class="v-nav-item v-nav-item__child v-caret__line" 
            v-for="(item2, indx2) in menu.items" 
            :key="indx2"
          >
            <a
              href="#"
              class="v-row v-align-items-center v-justify-content-between v-nav-link"
              @click="toggleMenuSidebars(item2.id)"
              v-if="item2.items"
            >
              <div class="v-nav-link__text">
                <span class="v-nav-title">{{ item2.label }}</span>
              </div>
              <div class="v-nav-link__caret" v-if="item2.items">
                <i v-if="!item2.isShow" class="fa-solid fa-sort-down"></i>
                <i v-if="item2.isShow" class="fa-solid fa-sort-up"></i>
              </div>
            </a>
            <nuxt-link
              :to="item2.route"
              class="v-row v-align-items-center v-justify-content-between v-nav-link"
              v-if="!item2.items"
            >
              <div class="v-nav-link__text">
                <span class="v-nav-title">{{ item2.label }}</span>
              </div>
              <div class="v-nav-link__caret" v-if="item2.items">
                <i v-if="!item2.isShow" class="fa-solid fa-sort-down"></i>
                <i v-if="item2.isShow" class="fa-solid fa-sort-up"></i>
              </div>
            </nuxt-link>
            <ul
              class="v-nav__child" 
              v-if="item2.items"
              :class="item2?.isShow ? 'v-show__nav-child' : ''"
            >
              <li 
                class="v-nav-item v-nav-item__child v-caret__line" 
                v-for="(item3, indx3) in item2.items" 
                :key="indx3"
              >
                <nuxt-link :to="item3.route" class="v-nav-link">
                  <div class="v-nav-link__text">
                    <span class="v-nav-title">{{ item3.label }}</span>
                  </div>
                </nuxt-link>
              </li>
            </ul>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</template>
<script>
import { storeToRefs } from 'pinia';
import { useLayoutStore } from '~/stores/layout';

export default {
  setup() {
    // Destructuring state/getters of store
    const { menus } = storeToRefs(useLayoutStore());

    // Destructuring action of store
    const { toggleItemMenu } = useLayoutStore();

    function toggleMenuSidebars(id) {
      toggleItemMenu(id);
    }

    return {
      menus,
      toggleMenuSidebars,
    };
  }
}
</script>
<style lang="">
  
</style>
