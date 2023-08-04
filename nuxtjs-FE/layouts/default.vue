<template>
  <div>
    <div class="wrap-default row w-100 border rounded bg-dark text-white">
      <!-- Sidebar menu left master -->
      <div class="col-1 sidebar-menu-left-mst border rounded">
        sidebar left
      </div>

      <!-- Aside main master start -->
      <div class="col-11 aside-main-mst border rounded">
        <!-- Header aside start -->
        <div class="header-aside border rounded">
          header aside
        </div>

        <!-- Article start -->
        <div class="sidebar-menu-aside-left border rounded row">
          <!-- Sidebar menu article left start -->
          <div class="sidebar-menu-article-left col-1 border rounded">
            sidebar menu article left
          </div>

          <!-- Main content start -->
          <div class="main-content col-11 border rounded">
            main content
            <slot />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  
}
</script>
<style>
  
</style>