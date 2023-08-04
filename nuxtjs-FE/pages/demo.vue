<template>
  <div>

    <!-- Header start -->
    <menuHeader />
    <!-- Header end -->

    <!-- Main start -->
    <div class="main mt-5">
      <h1>Home</h1>

      <div class="row bg-rose-400 px-3">
        <div class="col-3">
          <alert />
        </div>
        <div class="col-3">
          <badge />
        </div>
        <div class="col-3">
          <collapse />
        </div>
      </div>

      <div class="mt-5 px-3">
        <h2>Demo import css only this file</h2>
        <span class="color-dark-blue">Color text is dark blue</span>
        <br>
        <button class="bg-dark-blue">Back ground is dark blue</button>
      </div>

      <div class="mt-5 px-3">
        <h2>Demo import css for all page</h2>
        <p class="color-deep-pink">Color text is deep pink</p>
        <button class="bg-deep-pink">Back ground is deep pink</button>
      </div>
    </div>
    <!-- Main end -->

  </div>
</template>

<script>
export default {
  data() {
    return {
      arr: [
        'abc',
        'xyz'
      ],
      address: '123, aaaaaaa',
      isActive: true
    }
  },
  beforeCreate() {
    console.log('Before create ------------------------------------');
    console.log('Check variable : ', this.isActive);
  },
  created() {
    console.log('Created ------------------------------------------');
    console.log('Check variable : ', this.isActive);
  },
  beforeMount() {
    console.log('Before mount -------------------------------------')
    console.log('Check el : ', this.$el);
  },
  mounted() {
    console.log('Mounted ------------------------------------------');
    console.log('Check el : ', this.$el);
  },
  beforeUpdate() {
    console.log('Before update -------------------------------------');
  },
  updated() {
    console.log('Updated -----------------------------------------');
  },
  beforeDestroy() {
    console.log('Before destroy-------------------------------------')
  },
  destroyed() {
    console.log('Destroyed ----------------------------------------');
  },
  beforeUnmount() {
    console.log('Before unmount ------------------------------------');
  },
  unmounted() {
    console.log('Unmounted ---------------------------------------');
  },
}
</script>

<style>
  @import url('~/assets/css/home.css');
</style>