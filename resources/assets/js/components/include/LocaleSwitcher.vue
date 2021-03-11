<template>
<li class="nav-item-lang">
  <a href="#"   @click="switchLocale('en')"><i class="flag-icon flag-icon-gb"></i></a>
  &nbsp;|&nbsp;
  <a href="#"   @click="switchLocale('ar')"><i class="flag-icon flag-icon-ae"></i></a>
</li>
</template>
<script>
export default {
  
  data() {
    return {
      locales: ['en','ar']
    }
  },
   created(){
   
 
 },
  methods: { 
 
    switchLocale(locale) {
       this.$root.switchLocale(locale);
        location.reload();
    },
  }
 
}
</script>
<style scoped>
 
</style>