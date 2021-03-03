<template>
    <v-app>
        <div class="container-fluid  dashboard-content">
            <div class="row" v-if="loader">
                <div class="offset-5 col-md-6 col-sm-12">
                    <v-progress-circular
                    :size="70"
                    :width="7"
                    color="primary"
                    indeterminate
                    ></v-progress-circular>
                </div>
            </div>
            <div v-else>
                <div class="row">
                    <div class="col-md-5">
                        <h4>Cliente: </h4>
                        <model-select
                            :options="clientes"
                            v-model="entrada.cliente_id"
                        ></model-select>
                    </div>
                    <div class="col-md-3">
                        <h4>No. pedido</h4>
                        <input type="number" v-model.number="entrada.num_pedido"
                        @keypress.enter="buscarPedido" 
                        class="form-control">
                    </div>
                    <div class="col-md-4">
                        <h4>Fecha de factura</h4>
                        <input type="date" class="form-control" v-model="entrada.fecha">
                    </div>
                    <div class="col-md-5">
                        <div class="card">
                            <h5 class="card-header">Productos</h5>
                            <div class="card-body border-top">
                                <div class="row">
                                    <div class="col-md-12">
                                        <basic-select :options="productos"
                                            :selected-option="item"
                                            @select="selectProducto"
                                            placeholder="Buscar producto"
                                        ></basic-select>                                      
                                    </div>                                    
                                    <div class="col-md-4 col-sm-12">
                                        <h3>Cantidad</h3>
                                        <input type="number" class="form-control" v-model.number="producto.cantidad" min="0">   
                                    </div>
                                    <div class="col-md-8 col-sm-12">
                                        <h3>Precio</h3>
                                        <input type="number" class="form-control" v-model.number="producto.precio">   
                                    </div>
                                    <div class="col-md-12 col-sm-12">
                                        <p>Disponible en inventario: <span :class="{'text-danger': producto.stock === 0}">{{producto.stock}}</span></p>
                                    </div>
                                    <div class="col-md-12 col-sm-12">
                                        <v-btn color="primary" dark @click="agregarProducto"> 
                                            Agregar Producto
                                            <v-icon dark right>
                                                mdi-checkbox-marked-circle
                                            </v-icon>
                                        </v-btn>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="card">
                            <h5 class="card-header">Productos facturados</h5>
                            <div class="card-body border-top">
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
                                                    V.unit
                                                </th>
                                                <th class="text-left" width="20%">
                                                    V.total
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(item, index) in entradas" :key="index">
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
                                                <td>{{item.cantidad}}</td>
                                                <td>{{item.precio | currency}}</td>
                                                <td>{{item.precio * item.cantidad | currency}}</td>
                                            </tr>
                                        </tbody>
                                        </template>
                                    </v-simple-table>
                                </template> 
                                <p>
                                    <span class="fa-xs text-primary mr-1 legend-title"><i class="fa fa-fw fa-square-full"></i></span><span class="legend-text"> Total Factura</span>
                                    <span class="float-right"><h3>{{ totalFactura | currency }}</h3></span>
                                </p>
                                <v-btn class="indigo ma-2" dark @click="updatePedido" v-if="btnUpdate">
                                    Actualizar pedido
                                    <v-icon dark right>
                                        mdi-content-save
                                    </v-icon>
                                </v-btn>
                                <v-btn class="success ma-2" dark @click="savePedido" v-else>
                                    Guardar pedido
                                    <v-icon dark right>
                                        mdi-content-save
                                    </v-icon>
                                </v-btn>
                                <v-btn class="secondary ma-2" dark @click="reset">
                                    Cancelar
                                </v-btn>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" v-if="mensajeError">
                        <v-alert
                            dense
                            outlined
                            type="error"
                            >
                            <ul>
                                <li v-for="(item, index) in items" :key="index"></li>
                            </ul>
                        </v-alert>
                    </div>
                </div>
            </div>
        </div>
    </v-app>
</template>
<script>
    import { BasicSelect, ModelSelect} from 'vue-search-select';
    export default {
        data() {
            return {
                btnUpdate: false,
                clientes: [],
                entrada: {cliente_id: '', fechaentrada_id: '', num_pedido: '', fecha: '', entradas: [], valor: 0},
                entradas: [],
                errorEntrada: [],
                item: {
                    value: '',
                    text: ''
                },
                loader: true,
                mensajeError: false,
                producto: {id: '', producto: '', precio: 0, cantidad: 0, stock: 0},
                productos: [],
                totalFactura: 0
            }
        },
        mounted() {
            this.getProductos()
            this.getClientes()
        },
        methods: {
            agregarProducto(){
                if (this.producto.id === '' || this.producto.cantidad === 0 || this.producto.precio === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Hay campos vacios, completar',
                    });
                    return;
                }
                this.entradas.unshift({
                    id: this.producto.id,
                    fechaentrada_id: this.producto.fechaentrada_id,
                    cantidad: this.producto.cantidad,
                    producto: this.producto.producto,
                    stock: this.producto.stock,
                    precio: this.producto.precio,
                });
                this.calculaTotalFactura();
                this.item = {value: '', text: ''}

                this.producto.id = '';
                this.producto.producto = '';
                this.producto.cantidad = 0;
                this.producto.precio = 0;
                this.producto.stock = 0;
            },
            buscarPedido(){

                this.entradas = [];
                this.totalFactura = 0;
                axios.get(`/pedidos-calox/${this.entrada.num_pedido}`)
                    // this.reset()
                    .then(res => {
                        console.log(res.data)
                        this.entrada = Object.assign({}, res.data[0].ventas);
                        for (let i = 0; i < res.data.length; i++) {
                            this.entradas.push({
                                id: res.data[i].detalleproducto_id,
                                fechaentrada_id: res.data[i].id,
                                cantidad: res.data[i].cantidad,
                                producto: res.data[i].productos.producto + ' - '+res.data[i].presentaciones.presentacion,
                                stock: res.data[i].detalle.stock - res.data[i].cantidad,
                                precio: res.data[i].detalle.precio,
                            })
                        }
                        this.calculaTotalFactura();
                        this.btnUpdate = true;
                        
                    })
                    .catch(err => {
                        console.log(err)
                    })
            },
            calculaTotalFactura(){

                this.totalFactura = 0;
                for (let i = 0; i < this.entradas.length; i++) {
                    this.totalFactura += this.entradas[i].cantidad * this.entradas[i].precio;                   
                }
            },
            editar(producto, index){

                this.item.value = producto.id;
                this.item.text = producto.producto;

                this.producto.id = producto.id;
                this.producto.fechaentrada_id = producto.fechaentrada_id;
                this.producto.producto = producto.producto;
                this.producto.cantidad = producto.cantidad;
                this.producto.stock = producto.stock;
                this.producto.precio = producto.precio;

                this.entradas.splice(index, 1);

                this.calculaTotalFactura(); //Calcula nuevamente el total del pedido
            },   
            getClientes(){
                axios.get('/clientes')
                    .then(res => {
                        res.data.map((el) => {
                            if(el.tipocliente_id === 2){
                                this.clientes.push({
                                    text: el.razon_social,
                                    value: el.id
                                });
                            }
                        })
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
                        this.loader = false;
                    })
                    .catch(err => {
                        console.log(err)
                    })
            },
            reset(){
                this.entrada = {};
                this.entradas = [];
                this.totalFactura = 0;
                this.btnUpdate = false;
            },
            selectProducto(item){
                // console.log(option)
                this.producto.cantidad = 0;                
                this.producto.producto = item.text;
                this.producto.precio = item.precio;
                this.producto.stock = item.stock;
                this.producto.id = item.value;
                this.item = item;
                
            },
            savePedido(){
                Swal.showLoading()
                this.entrada.entradas = this.entradas;
                this.entrada.valor = this.totalFactura;

                if (this.entrada.num_pedido === '' || this.entrada.fecha === '' || this.entrada.entradas.length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Hay campos vacios (Numero de factura) / (Fecha) / (Productos)',
                    })
                    return;
                }

                axios.post('/pedidos-calox', this.entrada)
                    .then(res => {
                        if(res.data === 'ok'){                           
                            this.reset();
                            Swal.fire({
                                icon: 'success',
                                title: 'Enhorabuena!!',
                                text: 'Guardado exitoso'
                            });
                        }else{
                            this.mensajeError = true;
                            this.errorEntrada = res.data;
                        }
                    })
            },
            updatePedido(){
                Swal.showLoading()
                this.entrada.entradas = this.entradas;
                this.entrada.valor = this.totalFactura;
                
                if (this.entrada.num_pedido === '' || this.entrada.fecha === '' || this.entrada.entradas.length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Hay campos vacios (Numero de factura) / (Fecha) / (Productos)',
                    })
                    return;
                }

                axios.put(`/pedidos-calox/${this.entrada.id}`, this.entrada)
                    .then(res => {
                        if(res.data === 'ok'){
                           /*  this.entrada = {num_pedido : '', fecha: '', entradas: []};
                            this.entradas = [];
                            this.totalFactura = 0; */
                            this.reset();
                            Swal.fire({
                                icon: 'success',
                                title: 'Enhorabuena!!',
                                text: 'Actualizado exitoso'
                            });
                        }else{
                            this.mensajeError = true;
                            this.errorEntrada = res.data;
                        }
                    })
            }
        },
        computed:{
        },
        components:{
            BasicSelect,
            ModelSelect
        }
    }
</script>