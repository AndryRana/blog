<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
          <h1>Menu</h1> 
        <router-view></router-view>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    console.log("Component mounted.");
  },
};
</script>
