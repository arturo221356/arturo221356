/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import BootstrapVue from 'bootstrap-vue' //Importing

Vue.use(BootstrapVue) 

import Multiselect from 'vue-multiselect'

// register globally
Vue.component('multiselect', Multiselect)



// Telling Vue to use this in whole applicat

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('inventario-component', require('./components/InventarioComponent.vue').default);
Vue.component('select-sucursal', require('./components/SelectSucursalComponent.vue').default);
Vue.component('checkbox-status', require('./components/StatusCheckboxComponent.vue').default);
Vue.component('radio-producto', require('./components/RadioProductoComponent.vue').default);
Vue.component('export-excel', require('./components/ExportExcelComponent.vue').default);
Vue.component('table-component', require('./components/TableComponent.vue').default);
Vue.component('edit-modal', require('./components/EditModalComponent.vue').default);
Vue.component('select-status', require('./components/SelectStatusComponent.vue').default);
Vue.component('select-equipo', require('./components/SelectEquipoComponent.vue').default);
Vue.component('cargar-inventario', require('./components/CargarInvComponent.vue').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
