<template lang="">
  <div>

    <!-- Header start -->
    <menuHeader />
    <!-- Header end -->

    <!-- Main -->
    <div class="main bg-gray mt-5">
      <h1>contact</h1>
      <div class="row px-3">
        <div class="col-3">
          <badge />
        </div>
        <div class="col-3">
          <alert />
        </div>
        <div class="col-3">
          <collapse />
        </div>
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
      name: 'abc'
    }
  },
  computed: {
    now: function () {
      console.log('Run method now()');
      return Date.now();
    },
    getName: function () {
      console.log('Run method getName()');
      return this.name;
    }
  },
  updated() {
    console.log('Computed is method calculator, if dependence not change this not calculator again');
    console.log('Now : ', this.now);
    console.log('Get name : ', this.getName);
  },
}
</script>
<style lang="">
  
</style>