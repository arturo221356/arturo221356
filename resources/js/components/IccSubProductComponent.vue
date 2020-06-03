<template>
  <div>
     {{product_id}} --- {{products}}
  </div>
</template>

<script>
export default {
    data(){
        return{
            products:"",
        }
    },
    props:{
        product_id: Number,
    },
    methods:{
        loadSubProduct() {
            
            axios
                .get(`/admin/productos/sims/${this.product_id}`)
                .then((response) => {
                    this.products = response.data;
                    
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
        },
    },
    created(){
        this.loadSubProduct();
    }

}
</script>

<style>

</style>