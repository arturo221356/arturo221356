<template>
    <div>
        <b-overlay :show="isLoading" rounded="sm">
            <b-navbar
                toggleable="lg"
                type="light"
                style="background-color: #dedede;"
            >
                <b-navbar-brand href="#">Preactivar</b-navbar-brand>

                <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

                <b-collapse id="nav-collapse" is-nav>
                    <b-navbar-nav> </b-navbar-nav>

                    <!-- Right aligned nav items -->
                    <b-navbar-nav class="ml-auto"> </b-navbar-nav>
                    <div></div>
                </b-collapse>
            </b-navbar>
            <div class="jumbotron jumbotron-fluid">
                <div class="col-md-8 mx-auto">
                    <h1>Preactivar lineas:</h1>

                    <validation-observer
                        ref="observer"
                        v-slot="{ handleSubmit }"
                        v-show="lineaDetail == false"
                    >
                        <b-form @submit.prevent="handleSubmit(verificarIcc)">
                            <ValidationProvider
                                name="icc"
                                v-slot="validationContext"
                                rules="required|Icc|numeric"
                            >
                                <b-form-group label="Icc:" label-size="lg">
                                    <b-input-group prepend="Icc">
                                        <b-form-input
                                            v-model.lazy="icc"
                                            type="number"
                                            list="search-results"
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                            placeholder="Agregar Icc"
                                        ></b-form-input>
                                        <datalist id="search-results">
                                            <option
                                                v-for="(list,
                                                index) in searchResults"
                                                :key="index"
                                                >{{ list.title }}</option
                                            >
                                        </datalist>

                                        <b-input-group-append>
                                            <b-button
                                                variant="success"
                                                type="submit"
                                                >Agregar</b-button
                                            >
                                        </b-input-group-append>
                                        <b-form-invalid-feedback>{{
                                            validationContext.errors[0]
                                        }}</b-form-invalid-feedback>
                                    </b-input-group>
                                </b-form-group>
                            </ValidationProvider>
                            <b-form-group label="Excel" label-size="lg">
                                <b-form-file
                                    v-model="file"
                                    :state="Boolean(file)"
                                    placeholder="Agregar archivo Excel"
                                    drop-placeholder="Arrastra el archivo aqui"
                                    browse-text="Excel"
                                    accept=".xlsx, .csv"
                                ></b-form-file>
                            </b-form-group>
                        </b-form>
                    </validation-observer>
                    <validation-observer
                        v-if="lineaDetail == true"
                        v-slot="{ handleSubmit }"
                    >
                        <div class="row">
                            <div class="col-sm">
                                <h1
                                    class="float-right"
                                    :style="{ cursor: 'pointer' }"
                                >
                                    <b-icon
                                        icon="x-circle-fill"
                                        variant="danger"
                                        @click="lineaDetail = false"
                                    ></b-icon>
                                </h1>
                            </div>
                        </div>
                        <b-form @submit.prevent="handleSubmit(agregarIcc)">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>
                                        <B>Compañia:</B>
                                        {{ currentIcc.company }}
                                    </h4>
                                </div>

                                <div class="col-md-12">
                                    <h4><B>Tipo:</B> {{ currentIcc.type }}</h4>
                                </div>

                                <div class="col-md-12">
                                    <h4><B>Icc:</B> {{ currentIcc.icc }}</h4>
                                </div>
                            </div>

                            <ValidationProvider
                                rules="numeric|required|length:10"
                                v-slot="validationContext"
                                name="Numero"
                            >
                                <b-form-group label="Numero" label-size="lg">
                                    <b-input
                                        v-model="currentIcc.dn"
                                        :state="
                                            getValidationState(
                                                validationContext
                                            )
                                        "
                                        placeholder="Insertar numero"
                                    ></b-input>
                                    <b-form-invalid-feedback>{{
                                        validationContext.errors[0]
                                    }}</b-form-invalid-feedback>
                                </b-form-group>
                            </ValidationProvider>

                            <b-button type="submit" block
                                >Agregar linea</b-button
                            >
                        </b-form>
                    </validation-observer>

                    <b-button
                        block
                        variant="success"
                        class="mt-3"
                        v-if="preactivarButtonVisible"
                        @click="preactivarIcc"
                        >Preactivar</b-button
                    >

                    <b-list-group class="mt-5">
                        <b-list-group-item
                            v-for="(articulo, index) in iccs"
                            :key="index"
                        >
                            {{ index + 1 }} :
                            <strong>{{ articulo.icc }}</strong>
                            <small>{{ articulo.company }}</small>
                            <small>{{ articulo.type }}</small>
                            <small>{{ articulo.dn }}</small>

                            <b-button
                                size="sm"
                                class="float-right"
                                variant="danger"
                                @click="eliminarIcc(articulo, index)"
                                >Eliminar</b-button
                            >
                        </b-list-group-item>
                    </b-list-group>
                </div>
            </div>
        </b-overlay>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            icc: null,
            searchResults: [],
            iccs: [],
            lineaDetail: false,
            file: null,

            currentIcc: {
                icc: null,
                company: null,
                type: null,
                dn: null,
            },
        };
    },
    methods: {
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        verificarIcc() {
            if (this.iccs.some((item) => item.icc === this.icc)) {
                alert("Icc duplicado");
            } else {
                axios
                    .post("/linea/verificar-icc", { icc: this.icc })
                    .then((response) => {
                        console.log(response.data);

                        if (response.data.success == true) {
                            this.lineaDetail = true;

                            this.currentIcc.icc = response.data.data.icc;

                            this.currentIcc.company =
                                response.data.data.company.name;

                            this.currentIcc.type = response.data.data.type.name;

                            this.icc = null;
                        } else {
                            alert(response.data.message);
                        }
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    });
            }
        },
        agregarIcc() {
            if (this.iccs.some((item) => item.dn === this.currentIcc.dn)) {
                alert("Numero duplicado");
            } else {
                const serie = this.currentIcc;

                this.iccs.unshift({ ...serie });

                this.icc = null;

                this.currentIcc = {
                    icc: null,
                    company: null,
                    type: null,
                    dn: null,
                };
                this.lineaDetail = false;
            }
        },
        eliminarIcc(item, index) {
            this.iccs.splice(index, 1);
        },
        preactivarIcc() {
            const settings = {
                headers: {
                    "content-type": "multipart/form-data",
                },
            };

            const data = new FormData();
            data.append("data", JSON.stringify(this.iccs));
            data.append("file", this.file);
            axios
                .post("/preactivar-prepago", data, settings)
                .then((response) => {
                    console.log(response.data);

                    if (response.data.success == true) {
                        this.lineaDetail = true;

                        this.currentIcc.icc = response.data.data.icc;

                        this.currentIcc.company =
                            response.data.data.company.name;

                        this.currentIcc.type = response.data.data.type.name;

                        this.icc = null;
                    } else {
                        alert(response.data.message);
                    }
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
        },
    },
    watch: {
        icc: function (val) {
            self = this;
            if (val >= 5) {
                axios
                    .get("/search/traspaso-prediction", {
                        params: { search: val },
                    })
                    .then(function (response) {
                        self.searchResults = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            } else {
                self.searchResults = [];
            }
        },
    },
    computed:{
        preactivarButtonVisible: function(){
            if(this.lineaDetail == true){
                return false
            }else if(this.file == null && this.iccs.length == 0){
                return false 
            }else{
                return true
            }
        }
    }
};
</script>

<style></style>
