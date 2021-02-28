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
                <v-card>
                    <v-card-title>
                    Productos en stock
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
                    :items="productos"
                    :search="search"
                    ></v-data-table>
                </v-card>
            </div>
        </div>
    </v-app>
</template>
<script>
    export default {
        data() {
            return {
                headers: [
                    {text: 'Producto', value:'producto'},
                    {text: 'Stock', value: 'stock'}
                ],
                loader: true,
                productos: [],
                search: ''
            }
        },  
        mounted() {
            this.getProductos()
        },
        methods: {
            getProductos(){
                axios.get('/detalleproductos')
                    .then(res => {
                        // console.log(res.data)
                        for (let i = 0; i < res.data.length; i++) {
                            if (res.data[i].stock > 0) {                                
                                this.productos.push({
                                    producto: res.data[i].productos.producto +' - '+  res.data[i].presentaciones.presentacion,
                                    stock: res.data[i].stock
                                });                            
                            }
                        }
                        this.loader = false;
                    })
                    .catch(err => {
                        console.log(err)
                    })
            },
        },
    }
</script>