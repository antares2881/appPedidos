require('./bootstrap');

window.Vue = require('vue');

import Vuetify from '../plugins/vuetify'
import 'vue-search-select/dist/VueSearchSelect.css'
import VueCurrencyFilter from 'vue-currency-filter';

import Vue from 'vue';
import VueCurrencyInput from 'vue-currency-input';
import Swal from 'sweetalert2';

const pluginOptions = {
    globalOptions: { currency: {prefix: '$'}, locale: 'ES' , precision: 0, valueRange: { min: 0 }}
}

Vue.use(VueCurrencyInput, pluginOptions);
window.Swal = Swal;

Vue.use(VueCurrencyFilter, {
    symbol: '$', // El símbolo, por ejemplo €
    fractionCount: 0, // ¿Cuántos decimales mostrar?
    fractionSeparator: ',', // Separador de decimales
    symbolPosition: 'front', // Posición del símbolo. Puede ser al inicio ('front') o al final ('') es decir, si queremos que sea al final, en lugar de front ponemos una cadena vacía ''
    symbolSpacing: true // Indica si debe poner un espacio entre el símbolo y la cantidad
});

Vue.component('combos-component', require('./components/Combos.vue').default);
Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('facturas-component', require('./components/Facturas.vue').default);
Vue.component('gestioncombos-component', require('./components/Gestioncombos.vue').default);
Vue.component('home-component', require('./components/Home.vue').default);
Vue.component('inventario-component', require('./components/Inventario.vue').default);
Vue.component('login-component', require('./components/Login.vue').default);
Vue.component('llamadas-component', require('./components/Llamadas.vue').default);
Vue.component('modaldetalleproducto-component', require('./components/ModalDetalleProducto.vue').default);
Vue.component('modalproducto-component', require('./components/ModalProducto.vue').default);
Vue.component('stock-component', require('./components/Stock.vue').default);
Vue.component('transferencia-component', require('./components/Transferencia.vue').default);
Vue.component('printransfer-component', require('./components/ImprimirTransferencia.vue').default);
Vue.component('detalleproductos-component', require('./components/DetalleProductos.vue').default);
Vue.component('pedidoscalox-component', require('./components/PedidoCalox.vue').default);
Vue.component('productos-component', require('./components/Producto.vue').default);
Vue.component('productoscombo-component', require('./components/ProductosCombos.vue').default);
Vue.component('user-component', require('./components/User.vue').default);

const app = new Vue({
    vuetify: Vuetify,
    el: '#app',
});
