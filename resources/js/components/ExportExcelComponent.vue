<template>
  <b-button variant="success" :href="exportUrl">Excel</b-button>  
</template>
<script>
export default {
  
  data(){
    return{
    exportUrl : '/export/imeis/',



    }
  },
  
  methods:{



  }
}
</script>