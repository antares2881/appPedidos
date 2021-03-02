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
                <v-breadcrumbs
                    :items="itemsBreadcrumbs"
                    divider=" / "
                ></v-breadcrumbs>
                <modaldetalleproducto-component ref="modalDetalleProducto"/>
                <v-card>
                    <v-card-title>
                        <v-btn
                            color="primary"
                            dark
                            class="ma-2"
                            @click="nuevoProducto"
                        >Nuevo producto
                            <v-icon right>
                                mdi-checkbox-marked-circle
                            </v-icon>
                        </v-btn>
                    <v-spacer></v-spacer>
                    <v-text-field
                        v-model="search"
                        append-icon="mdi-magnify"
                        label="Buscar"
                        single-line
                        hide-details
                    ></v-text-field>
                    </v-card-title>
                    <v-data-table
                    :headers="headers"
                    :items="products"
                    :search="search"
                    >
                        <template v-slot:item.precio="{item}">
                            {{item.precio | currency}}
                        </template>
                        <template v-slot:item.acciones="{item}">
                            <v-btn
                                color="indigo"
                                dark
                                outlined
                                @click="editarProducto(item)"
                            >
                                <v-icon>
                                    mdi-pencil
                                </v-icon>
                            </v-btn>
                        </template>
                    </v-data-table>
                </v-card>
            </div>
        </div>
    </v-app>
</template>
<script>
    export default {
        props: ['productos'],
        data() {
            return {
                headers: [
                    {text: 'Producto', value: 'producto'},
                    {text: 'Presentacion', value: 'presentacion'},
                    {text: 'Codigo', value: 'codigo'},
                    {text: 'Precio', value: 'precio'},
                    {text: 'stock', value: 'stock'},
                    {text: 'Accion', value: 'acciones'},
                ],
                itemsBreadcrumbs: [],
                loader: true,
                products: [],
                search: ''
            }
        },
        mounted() {
            this.setProductos()
            // console.log(this.productos)
        },
        methods: {
            editarProducto(item){
                this.$refs.modalDetalleProducto.editProduct(item);
            },
            nuevoProducto(){
                this.$refs.modalDetalleProducto.newProduct(this.products[0].producto_id, this.products[0].producto);
            },
            setProductos(){
                this.productos.map((el) => {
                    this.products.push({
                        id: el.id,
                        codigo: el.codigo,
                        producto_id: el.producto_id,
                        producto: el.productos.producto,
                        presentacione_id: el.presentacione_id,
                        presentacion: el.presentaciones.presentacion,
                        precio: el.precio,
                        stock: el.stock,
                    });
                });
                this.itemsBreadcrumbs = [
                    {text: 'Regresar', href: '../productos', disabled: false},
                    {text: this.productos[0].productos.producto, href: 'productos', disabled: true},
                ]
                this.loader = false;
            }
        },
    }
</script>