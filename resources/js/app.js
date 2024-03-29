/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import { BootstrapVue, BootstrapVueIcons } from 'bootstrap-vue'

Vue.use(BootstrapVue) 

Vue.use(BootstrapVueIcons)


import Multiselect from 'vue-multiselect'

Vue.component('multiselect', Multiselect);




import { ValidationObserver, ValidationProvider} from 'vee-validate';

Vue.component('ValidationProvider', ValidationProvider);

Vue.component("ValidationObserver", ValidationObserver);

require('./validation');


import LaravelPermissionToVueJS from 'laravel-permission-to-vuejs';
Vue.use(LaravelPermissionToVueJS);


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




Vue.component('select-statuses', require('./components/SelectStatusesComponent.vue').default);
Vue.component('usuarios-component', require('./components/UsuariosComponent.vue').default);
Vue.component('equipos-component', require('./components/EquiposComponent.vue').default);
Vue.component('sucursales-component', require('./components/SucursalesComponent.vue').default);
Vue.component('select-general', require('./components/SelectGeneralComponent.vue').default);
Vue.component('preactivar-linea', require('./components/PreactivarComponent.vue').default);
Vue.component('activa-chip', require('./components/ActivaChip.vue').default);
Vue.component('linea-reporte', require('./components/LineaReporte.vue').default);
Vue.component('venta-reporte', require('./components/VentaReporte.vue').default);
Vue.component('transaction-reporte', require('./components/TransactionReporte.vue').default);
Vue.component('porta-express', require('./components/PortaExpress.vue').default);
Vue.component('cuentas-component', require('./components/CuentasComponent.vue').default);
Vue.component('otros-component', require('./components/NewOtrosComponent.vue').default);
Vue.component('cargar-inv-page', require('./components/CargarInvPage.vue').default);
Vue.component('reporte-inv-page', require('./components/ReporteInvPage.vue').default);
Vue.component('venta-page', require('./components/VentaPage.vue').default);
Vue.component('traspasos-page', require('./components/TraspasosPage.vue').default);
Vue.component('equipos-reporte-page', require('./components/EquiposReportePage.vue').default);
Vue.component('accesorio-reporte-page', require('./components/AccesorioReportePage.vue').default);
Vue.component('check-itx-component', require('./components/homeComponents/checkItxComponent.vue').default);
Vue.component('check-company-component', require('./components/homeComponents/checkCompanyComponent.vue').default);
Vue.component('icc-calculator-component', require('./components/homeComponents/iccCalculatorComponent.vue').default);
Vue.component('navbar-search-component', require('./components/homeComponents/navbarSearchComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
