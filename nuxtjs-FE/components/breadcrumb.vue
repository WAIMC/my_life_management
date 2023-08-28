<template lang="">
  <div>
    <!-- Breadcrumb -->
    <div class="v-breadcrumb__header v-row v-justify-content-between v-align-items-center">
      <div class="v-title__breadcrumb v-col-lg-6 v-col-md-6 v-col-sm-12 v-mt-1 v-mb-1">
        {{ titleBreadcrumb }}
      </div>
      <div class="v-breadcrumb__full-path v-col-lg-6 v-col-md-6 v-col-sm-12 v-mt-1 v-mb-1">
        <div class="v-row v-justify-content-end v-align-items-center">
          <div class="v-breadcrumb__path">
            {{ generatePath }}
          </div>
          <div class="v-breadcrumb__link">
            <nuxt-link :to="fullPath">{{ nameBreadcrumb }}</nuxt-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { computed } from 'vue';
import { useRoute } from 'vue-router';

export default {
  props: {
    titleBreadcrumb: String,
  },
  setup() {
    const { fullPath } = useRoute();

    const itemsPath = computed(() => {
      return fullPath.split('/').splice(1);
    })

    const generatePath = computed(() => {
      let path = '';
      let arrFullPath = itemsPath.value;

      arrFullPath.splice(0, arrFullPath.length - 1).forEach((val) => {
        path += val.replace(/\W/g, ' ').charAt(0).toUpperCase() + val.replace(/\W/g, ' ').slice(1) + ' / ';
      })

      return path;
    })

    const nameBreadcrumb = computed(() => {
      let name = itemsPath.value[itemsPath.value.length - 1];

      return name.replace(/\W/g, ' ').charAt(0).toUpperCase() + name.replace(/\W/g, ' ').slice(1);
    })

    return {
      fullPath,
      generatePath,
      nameBreadcrumb,
    };
  }
}
</script>
<style lang="">
  
</style>