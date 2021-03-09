<template>
    <v-app>
        <div class="container-fluid  dashboard-content" v-if="loader">           
            <div class="row">
                <div class="offset-5 col-md-6 col-sm-12">
                    <v-progress-circular
                    :size="70"
                    :width="7"
                    color="primary"
                    indeterminate
                    ></v-progress-circular>
                </div>
            </div>
        </div>
        <div class="container-fluid  dashboard-content" v-else>
            <facturas-component ref="facturar" />
            <div class="row" >
                <div class="col-md-5 col-sm-12" v-if="admin">
                    <h4>Cliente: </h4>         
                    <model-select :options="clientes"
                        placeholder="Buscar Cliente"
                        v-model="pedido.cliente_id"
                    ></model-select> 
                </div>
                <div class="col-sm-6" :class="{'col-md-3' : admin, 'col-md-5' : !admin}">
                    <h4>No. transferencia</h4>
                    <input type="number" class="form-control" v-model="pedido.numero" @keypress.enter="findTransferencia">
                </div>
                <div class="col-sm-6" :class="{'col-md-4' : admin, 'col-md-7' : !admin}">
                    <h4>Fecha</h4>
                    <input type="date" class="form-control" v-model="pedido.fecha">
                </div>
            </div>
            <div class="row">
                <div class="col-md-5 col-sm-12">
                    <div class="card">
                        <h5 class="card-header">Productos</h5>
                        <div class="card-body border-top">
                            <div class="row">
                                <div class="col-md-12">
                                    <basic-select :options="productos"
                                        :selected-option="item"
                                        @select="calcularPrecio"
                                        placeholder="Buscar producto"
                                    ></basic-select>                                        
                                </div>
                                <div class="col-md-12">
                                    <template>
                                        <v-simple-table width="100%">
                                            <template v-slot:default>
                                            <thead>
                                                <tr>
                                                    <th class="text-left">
                                                        Cant.
                                                    </th>
                                                    <th class="text-left">
                                                        Entregar
                                                    </th>
                                                    <th class="text-left">
                                                        V.unit
                                                    </th>
                                                    <th class="text-left">
                                                        V.total
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td width="25%">
                                                        <input type="number" class="form-control" v-model.number="producto.cantidad" min="0">              
                                                    </td>
                                                    <td width="25%">
                                                        <input 
                                                            type="number"
                                                            class="form-control"
                                                            v-model.number="producto.entregar"
                                                            min="0"
                                                            :max="producto.stock"
                                                            :readonly="producto.stock === 0 || role === 2?true:false"
                                                        >
                                                    </td>
                                                    <td>{{producto.precio | currency}}</td>
                                                    <td>{{calculoTotalProducto | currency}}</td>
                                                </tr>
                                            </tbody>
                                            </template>
                                        </v-simple-table>
                                        <div class="col-md-12 col-sm-12" v-if="role === 1">
                                            <p>Disponible en inventario: <span :class="{'text-danger': producto.stock === 0}">{{producto.stock}}</span></p>
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                            <v-btn class="ma-2" color="primary" dark @click="agregarProducto"> 
                                                Agregar Producto
                                                <v-icon dark right>
                                                    mdi-checkbox-marked-circle
                                                </v-icon>
                                            </v-btn>
                                        </div>
                                    </template>                        
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 col-sm-12">
                    <div class="card">
                        <h5 class="card-header">Pedido</h5>
                        <div class="card-body">
                            <div class="">
                                <div class="col-md-12">
                                    <template>
                                        <v-simple-table width="100%">
                                            <template v-slot:default>
                                            <thead>
                                                <tr>
                                                    <th class="text-left">
                                                        Editar
                                                    </th>
                                                    <th class="text-left">
                                                        Producto
                                                    </th>
                                                    <th class="text-left">
                                                        Cant.
                                                    </th>
                                                    <th class="text-left" width="20%">
                                                        Valor total
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(item, index) in pedidos" :key="index">
                                                    <td>
                                                        <v-btn
                                                            color="primary"
                                                            fab
                                                            x-small
                                                            dark
                                                            outlined
                                                            @click="editar(item, index)"
                                                        >
                                                            <v-icon>mdi-pencil</v-icon>
                                                        </v-btn>
                                                    </td>
                                                    <td>{{item.producto}}</td>
                                                    <td>{{item.cantidad}} ({{item.entregar}})</td>
                                                    <td>{{item.total | currency}}</td>
                                                </tr>
                                            </tbody>
                                            </template>
                                        </v-simple-table>
                                    </template> 
                                </div>
                                <p>
                                    <span class="fa-xs text-primary mr-1 legend-title"><i class="fa fa-fw fa-square-full"></i></span><span class="legend-text"> Total pedido</span>
                                    <span class="float-right"><h3>{{ totalPedido | currency }}</h3></span>
                                </p>
                               
                            </div>
                            <div class="col-md-6" v-if="!btnEdit">
                                <v-btn class="success ma-2" dark @click="savePedido">
                                    Guardar pedido
                                    <v-icon dark right>
                                        mdi-content-save
                                    </v-icon>
                                </v-btn>
                            </div>
                            <div class="col-md-12" v-else>
                                <v-btn class="ma-2" color="deep-orange" dark @click="updatePedido">
                                    Actualizar pedido
                                    <v-icon dark right>
                                        mdi-content-save
                                    </v-icon>
                                </v-btn>
                                <v-btn class="ma-2" color="indigo" dark @click="facturar">
                                    Facturar
                                    <v-icon dark right>
                                        mdi-currency-usd
                                    </v-icon>
                                </v-btn>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" v-if="respuestaPedido">
                    <v-alert type="success">
                        El pedido fue agregado. 
                    </v-alert>
                </div>
                <div class="col-md-12" v-if="errorPedido">
                    <v-alert type="error">
                        <ul>
                            <li v-for="(item, index) in errores" :key="index">
                                {{item}}
                            </li>
                        </ul>
                    </v-alert>
                </div>
            </div>
        </div> 
    </v-app>
</template>
<script>
    import { BasicSelect, ModelSelect } from 'vue-search-select';

    export default {
        data() {
            return {
                admin: true,
                btnEdit: false,
                clientes: [],
                errorPedido: false,
                errores: '',
                item: {
                    value: '',
                    text: ''
                },
                itemCliente: {
                    value: '',
                    text: ''
                },
                loader: true,
                pedido: {id: '', cliente_id: '', numero: '', fecha: '', pedidos: [], total: 0},
                pedidos: [],
                producto: {id: '', producto: '', precio: 0, total: 0, cantidad: 0, stock: 0, entregar: 0},
                productos: [],
                respuestaPedido: false,
                return: false,
                role: '',
                totalPedido: 0
            }
        },
        mounted() {
            this.getProductos();
            this.getUser();
        },
        methods: {
            agregarProducto(){
                if(this.producto.producto === '' || this.producto.cantidad === 0 || this.producto.total === 0){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Hay campos vacios, completar',
                    })
                    return;
                }

                if(this.producto.cantidad < this.producto.entregar){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'El numero de productos a entregar no puede ser mayor a la cantidad solicitada',
                    })
                    return;
                }

                this.pedidos.push({
                    id: this.producto.id,
                    cantidad: this.producto.cantidad,
                    entregar: this.producto.entregar,
                    producto: this.producto.producto,
                    stock: this.producto.stock,
                    precio: this.producto.precio,
                    total: this.producto.total,
                });
                
                this.calculaTotalPedido();

                this.item = {value: '', text: ''}

                this.producto.id = '';
                this.producto.producto = '';
                this.producto.cantidad = 0;
                this.producto.precio = 0;
                this.producto.total = 0;
                this.producto.stock = 0;
                this.producto.entregar = 0;
            },
            calculaTotalPedido(){
                this.totalPedido = 0;
                for (let i = 0; i < this.pedidos.length; i++) {
                    this.totalPedido += this.pedidos[i].total;                   
                }
            },
            calcularPrecio(item){
                // console.log(option)
                this.producto.cantidad = 0;
                this.producto.precio = Math.round((parseInt(item.precio)*0.17) + parseInt(item.precio));
                this.producto.producto = item.text;
                this.producto.stock = item.stock;
                this.producto.id = item.value;
                this.item = item;
                
            },
            editar(producto, index){

                this.item.value = producto.id;
                this.item.text = producto.producto;

                this.producto.id = producto.id;
                this.producto.producto = producto.producto;
                this.producto.cantidad = producto.cantidad;
                this.producto.stock = producto.stock + producto.entregar;
                this.producto.entregar = 0;
                this.producto.total = producto.total;
                this.producto.precio = producto.total/producto.cantidad;

                this.pedidos.splice(index, 1);

                this.calculaTotalPedido(); //Calcula nuevamente el total del pedido
            },
            facturar(){
                this.pedido.pedidos = this.pedidos;
                this.pedido.total = this.totalPedido;
                this.$refs.facturar.confirmFactura(this.pedido);
            },
            findTransferencia(){
                this.pedidos = [];
                this.totalPedido = 0;
                // console.log(this.pedido.numero)  
                axios.get(`/numero-transferencia/${this.pedido.numero}`)  
                    .then(res => {
                        console.log(res.data[0])
                        this.pedido = {};

                        if(res.data.length > 0){
                        
                            this.pedido = Object.assign({}, res.data[0]);

                            if(this.pedido.estado_id === 2){
                                Swal.fire({
                                    icon: 'info',
                                    title: 'El pedido ya fue facturado'
                                });
                                return;
                            }else if(this.pedido.estado_id === 1){

                                axios.get(`/producto-transferencias/${res.data[0].id}`)
                                    .then(res => {
                                        console.log(res.data)
                                        for (let i = 0; i < res.data.length; i++) {
    
                                            let vUnit = Math.round((res.data[i].detalleproductos.precio*0.17) + res.data[i].detalleproductos.precio);
                                            let vTotal = res.data[i].cantidad * vUnit;
    
                                            this.pedidos.push({
                                                id: res.data[i].detalleproducto_id,
                                                cantidad: res.data[i].cantidad,
                                                precio: vUnit,
                                                entregar: res.data[i].entregados,
                                                stock: res.data[i].detalleproductos.stock,
                                                producto: res.data[i].productos.producto+' - '+res.data[i].presentaciones.presentacion,
                                                total: vTotal,
                                            });
                                        }
                                        this.calculaTotalPedido();
                                        this.btnEdit = true;
    
                                    })      
                                    .catch(err => {
                                        console.log(err)
                                    })
                            }
                            
                            
                        }else{
                            this.btnEdit = false;
                            this.pedido = {};
                        }
                    })
                    .catch(err => {
                        console.log(err)
                    })
                
            },
            getProductos(){
                axios.get('/detalleproductos')
                    .then(res => {
                        // console.log(res.data)
                        for (let i = 0; i < res.data.length; i++) {
                            this.productos.push({
                                value: res.data[i].id,
                                text: res.data[i].productos.producto +' - '+  res.data[i].presentaciones.presentacion,
                                precio: res.data[i].precio,
                                stock: res.data[i].stock
                            });                            
                        }
                    })
                    .catch(err => {
                        console.log(err)
                    })
            },
            getUser(){
                this.clientes = [];
                axios.get('/getUser')
                    .then(res => {
                        this.role = res.data.role_id;
                        if(res.data.role_id === 1){
                            axios.get('/clientes')
                                .then(res => {
                                    for (let i = 0; i < res.data.length; i++) {
                                        if (res.data[i].tipocliente_id === 1) {
                                            this.clientes.push({
                                                value: res.data[i].id,
                                                text: res.data[i].razon_social+' - '+res.data[i].municipios.mcpio
                                            });                                            
                                        }
                                    }
                                    this.loader = false;
                                })
                                .catch(err => {
                                    console.log(err)
                                })
                            
                        }else{
                            axios.get('/getCliente')
                                .then(res => {
                                    console.log(res.data)
                                    this.admin = false;
                                    this.loader = false;
                                })
                                .catch(err => {
                                    console.log(err)
                                })
                        }
                    })
                    .catch(err => {
                        console.log(err)
                    })
            },
            savePedido(){
                this.pedido.pedidos = this.pedidos;
                this.validar();
                if(this.return) return;
                
                this.pedido.total = this.totalPedido;
                axios.post('/transferencias', this.pedido)
                    .then(res => {
                        // console.log(res.data)
                        if(res.data === 'ok'){
                            this.respuestaPedido = true;
                            this.errorPedido = false;
                            this.pedido = {};
                            this.pedidos = [];
                            this.totalPedido = 0;
                        }else{
                            this.errorPedido = true;
                            this.errores = res.data;
                        }
                    })
                    .catch(err => {
                        console.log(err)
                    })
            },
            updatePedido(){
                this.pedido.pedidos = this.pedidos;
                this.validar();                
                if(this.return) return;

                this.pedido.total = this.totalPedido;
                axios.put(`/transferencias/${this.pedido.id}`, this.pedido)
                    .then(res => {
                        if(res.data === 'ok'){
                            this.respuestaPedido = true;
                            this.errorPedido = false;
                            this.pedido = {};
                            this.pedidos = [];
                            this.totalPedido = 0;
                            this.btnEdit = false;
                        }else{
                            this.errorPedido = true;
                            this.errores = res.data;
                        }
                    })
                    .catch(err => {
                        console.log(err)
                    })
                
            },
            validar(){

                this.return = false;

                if(this.pedido.cliente_id === ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Debes agregar un cliente para realizar el pedido'
                    });
                    this.return = true;
                }
                if(this.pedido.numero === ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Numero de transferencia vacio'
                    });
                    this.return = true;                    
                }
                if(this.pedido.fecha === ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Debes agregar una fecha de pedido'
                    });
                    this.return = true;
                }
                if(this.pedido.cliente_id === ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Debes agregar un cliente para realizar el pedido'
                    });
                    this.return = true;
                }
                if(this.pedido.pedidos.length === 0){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Debes agregar productos al pedido'
                    });
                    this.return = true;
                }
            }
        },       
        computed: {
            calculoTotalProducto(){                
                return this.producto.total =  this.producto.precio * this.producto.cantidad;
            }
        },
        components: {
            BasicSelect,
            ModelSelect
        }
    }
</script>
<style scoped>
    .v-progress-circular {
    margin: 1rem;
    }
</style>