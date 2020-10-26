<template>
    <div>
        <b-overlay :show="isLoading" rounded="sm">
            <div class="jumbotron jumbotron-fluid">
                <div>
                    <div class="col-xl-6 mx-auto">

                       <h3><B>Venta folio:</B> 1234</h3>

                       <h5><B>Cliente:</B> Arturo Alvarez Flores</h5>

                    </div>
                </div>
            </div>
        </b-overlay>
    </div>
</template>

<script>
export default {
    props: {
        ventaId: {
            required: true,
        },
    },
};
</script>

<style></style>
