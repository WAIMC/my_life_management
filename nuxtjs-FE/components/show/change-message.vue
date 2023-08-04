<template>
  <div>
    <div class="form-group">
      <label for="message">Change message</label>
      <input 
        type="text" 
        class="form-control" 
        name="tempText" 
        id="message" 
        aria-describedby="helpId" 
        placeholder="Input to change message..."
        @change="$emit('changeMessage', $event.target.value)"
      >
      <small id="helpId" class="form-text text-muted"></small>
    </div>
  </div>
</template>
<script>
export default {
}
</script>
<style>
  
</style>