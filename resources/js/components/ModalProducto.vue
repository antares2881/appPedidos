<template>
    <div class="text-center">
        <v-dialog
            v-model="dialogProductos"
            width="800"
            content-class="dialog-producto"
        >
            <v-card>
                <v-card-title class="headline">
                    {{titleModal}}
                </v-card-title>

                <v-card-text>
                    
                    <v-row>
                        <v-alert
                            dense
                            outlined
                            type="error"
                            v-if="errorValidacionProducto"
                            >{{variableVacia}}
                        </v-alert>                                         
                        <v-col cols="12">
                            <v-text-field
                                v-model="producto.producto"
                                label="Nombre del Producto"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-select
                                :items="categorias"
                                label="Categoria"
                                v-model="producto.categoria_id"
                            ></v-select>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-text-field
                                v-model="producto.composicione_id"
                                label="Composicion"
                                v-if="!showSelectComposicion"
                            ></v-text-field>
                            <v-select
                                :items="composiciones"
                                v-model="producto.composicione_id"
                                label="Composicion"
                                readonly
                                v-else
                            ></v-select>
                        </v-col>
                        <v-col cols="12">
                            <v-textarea
                                v-model="producto.descripcion"
                                label="Descripcion del producto"
                                ></v-textarea>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-text-field
                                v-model="producto.indicaciones"
                                label="Indicaciones de uso"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-text-field
                                v-model="producto.administracion"
                                label="Administracion"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-text-field
                                v-model="producto.precauciones"
                                label="Precauciones"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="secondary"
                        @click="dialogProductos = false"
                    >
                        Cancelar
                    </v-btn>
                    <v-btn
                        color="primary"
                        dark
                        @click="updateProducto"
                        v-if="showSelectComposicion"
                    >
                        Actualizar
                    </v-btn>
                    <v-btn
                        color="indigo"
                        dark
                        @click="saveProducto"
                        v-else
                    >
                        Guardar
                    </v-btn>
                </v-card-actions>
            </v-card>
            
        </v-dialog>
    </div>
</template>
<script>
    import {ModelSelect} from 'vue-search-select'
    export default {
        data() {
            return {
                categorias: [],
                composiciones: [],
                dialogProductos: false,
                errorValidacionProducto: false,
                producto: {id: '', categoria_id: '', composicion: '', composicione_id: '', producto: '', descripcion: '', indicaciones: '', administracion: '', precauciones: '', imagen: ''},
                showSelectComposicion: false,
                titleModal: '',
                variableVacia: []
            }
        },
        mounted() {
            this.getCategorias();
            this.getComposiciones();
        },
        methods: {
            close(){
                this.dialogProductos = false;
                this.$nextTick(() => {
                    this.producto = {};
                    this.variableVacia = [];
                    this.errorValidacionProducto = false;
                })
            },
            editProduct(item){
                this.producto = Object.assign({}, item);
                this.dialogProductos = true;
                this.titleModal = 'Editar producto';
                this.showSelectComposicion = true;
            },
            getCategorias(){
                axios.get('/categorias')
                    .then(res => {
                        res.data.map((el) => {
                            this.categorias.push({
                                text: el.categoria,
                                value: el.id
                            });
                        })
                    })
                    .catch(err => {
                        console.log(err)
                    })
            },
            getComposiciones(){
                axios.get('/composiciones')
                    .then(res => {
                        res.data.map((el) => {
                            this.composiciones.push({
                                text: el.composicion,
                                value: el.id
                            });
                        })
                    })
                    .catch(err => {
                        console.log(err)
                    })
            },
            newProduct(){
                this.showSelectComposicion = false;
                this.dialogProductos = true;
                this.titleModal = 'Nuevo producto';
            },
            saveProducto(){
                this.errorValidacionProducto = false;
                this.validarFormProducto();
                if (this.variableVacia.length > 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Campos vacios'
                    })
                    this.errorValidacionProducto = true;
                    return;
                }

                axios.post('/productos', this.producto)
                    .then(res => {
                        if(res.data === 'ok'){
                            Swal.fire({
                                icon:'success',
                                title: 'Guardado',
                                text: 'El producto fue agregado'
                            });
                            // this.productos.unshift(this.producto);
                            this.close();
                        }else{
                            this.errorValidacionProducto = true;
                            this.variableVacia = res.data;
                        }
                    })
            },
            updateProducto(){
                this.errorValidacionProducto = false;
                this.validarFormProducto();
                if (this.variableVacia.length > 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Campos vacios'
                    })
                    this.errorValidacionProducto = true;
                    return;
                }

                axios.put(`/productos/${this.producto.id}`, this.producto)
                    .then(res => {
                        if(res.data === 'ok'){
                            Swal.fire({
                                icon:'success',
                                title: 'Actualizado',
                                text: 'El producto fue actualizado'
                            });                            
                            this.close();
                        }else{
                            this.errorValidacionProducto = true;
                            this.variableVacia = res.data;
                        }
                    })
            },
            validarFormProducto(){
                this.variableVacia = [];
                if(this.producto.categoria_id === ''){
                    this.variableVacia.push('La categoria del producto es requerida')                    
                }
                if(this.producto.composicione_id === ''){
                    this.variableVacia.push('La composicion del producto es requerida')
                }
                if(this.producto.producto === ''){
                    this.variableVacia.push('El nombre del producto es requerido')
                }
                if(this.producto.descripcion === ''){
                    this.variableVacia.push('La Descripcion del producto es requerida')
                }
            }
        },
        watch: {
            dialogProductos (val) {
                val || this.close()
            }
        },
        components:{
            ModelSelect
        }
    }
</script>
<style >
    div.v-dialog__content.v-dialog__content--active{
        top: 30px;
    }
</style>