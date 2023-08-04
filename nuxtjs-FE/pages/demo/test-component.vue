<template>
  <div>
    <h2 class="text-danger">Demo component</h2>
    <div class="row border rounded">
      <show-calculator @onIncrement="increment" @onDecrement="decrement"/>
      <show-count :count="count"/>
    </div>
    <div class="border rounded">
      <show-change-message @changeMessage="changeMessage" />
      <show-message :message="message" />
    </div>
  </div>
</template>
<script lang="ts">
import { ref, onMounted, onUpdated} from 'vue'
import {testComponent} from '~/assets/js/testComponent';

export default {
  setup() {
    /**
     * Init data
     */
    const count = ref(0);
    const { message, changeMessage } = testComponent();

    /**
     * Method
     */
    const increment = () => {
      console.log('run increment');
      return count.value++;
    }

    const decrement = () => {
      console.log('run decrement');
      return count.value--;
    }

    return {count, message, increment, decrement, changeMessage};
  }
}
</script>
<style>
  
</style>