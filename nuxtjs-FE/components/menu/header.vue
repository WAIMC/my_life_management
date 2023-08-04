<template lang="">
  <div>
    <header>
      <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
        <div class="collapse navbar-collapse" id="collapsibleNavId">
          <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item active">
              <nuxt-link to="/" class="nav-link">Home</nuxt-link>
            </li>
            <li class="nav-item">
              <nuxt-link to="/about" class="nav-link">About</nuxt-link>
            </li>
            <li class="nav-item dropdown">
              <nuxt-link to="/contact" class="nav-link">Contact</nuxt-link>
            </li>
            <li class="nav-item dropdown">
              <nuxt-link to="/template-directive" class="nav-link">Template</nuxt-link>
            </li>
          </ul>
        </div>
      </nav>
    </header>
  </div>
</template>
<script>
export default {
  
}
</script>
<style lang="">
  
</style>