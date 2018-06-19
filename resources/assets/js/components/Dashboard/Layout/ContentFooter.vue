<template>
  <footer class="footer">
    <div class="container-fluid">
      <nav>
        <ul class="footer-menu">
          <li>
            <router-link :to="{path:'/admin'}">Dashboard</router-link>
          </li>
        </ul>
      </nav>
      <div class="copyright text-center">
        &copy; Integrated to laravel by 
        <a href="https://github.com/j574144" target="_blank">Joaquin Lozano C</a>.
        Initial <a href="https://github.com/cristijora/vue-light-bootstrap-dashboard" target="_blank"> Vue version </a> with
        <i class="fa fa-heart heart"></i> by
        <a href="https://github.com/cristijora" target="_blank">Cristi Jora</a>.
        Designed by <a href="https://www.creative-tim.com/?ref=pdf-vuejs" target="_blank">Creative Tim</a>.
      </div>
    </div>
  </footer>
</template>
<script>
  export default {}

</script>
<style>

</style>
