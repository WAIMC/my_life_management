<template>
  <div>
    <button type="button" class="btn btn-primary" @click="$emit('onIncrement')">+</button>
    <button type="button" class="btn btn-primary" @click="$emit('onDecrement')">-</button>
  </div>
</template>
<script>
export default {
}
</script>
<style>
  
</style>