<template>
    <div>
        <v-dialog
            v-model="dialogTransferencia"
            max-width="800px"
            >
            <v-card>
                <v-card-title class="headline">
                    Transferencia No. {{transferencia.numero}}
                </v-card-title>

                <v-card-text>
                    <h4>{{transferencia.cliente}}</h4>
                    <p><strong>Nit: </strong>{{`${transferencia.nit} - ${transferencia.dv}`}}</p>
                    <p>Fecha: {{transferencia.fecha}}</p>
                    <template>
                        <v-simple-table width="100%">
                            <template v-slot:default>
                                <thead>
                                    <tr>
                                        <th class="text-left">
                                            Producto
                                        </th>
                                        <th class="text-left">
                                            Cant.
                                        </th>
                                        <th class="text-left">
                                            V. Unit
                                        </th>
                                        <th class="text-left" width="20%">
                                            V. Total
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in detalleProductos" :key="index">
                                        <td>{{item.producto}}</td>
                                        <td>{{item.cantidad}}</td>
                                        <td>{{item.precio | currency}}</td>
                                        <td>{{item.total | currency}}</td>
                                    </tr>
                                </tbody>
                            </template>
                        </v-simple-table>
                    </template> 
                    <v-row class="mt-3">
                        <v-col
                            cols="12"
                            md="12"
                        >
                            <h4><Strong>Total pedido</Strong></h4>{{transferencia.valor | currency}}
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-card-actions>
                <v-spacer></v-spacer>

                <v-btn
                    color="green darken-1"
                    outlined
                    @click="dialogTransferencia = false"
                >
                    Imprimir
                </v-btn>

                <v-btn
                    color="green darken-1"
                    outlined
                    @click="dialogTransferencia = false"
                >
                    Cerrar
                </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>  
    </div>   
</template>
<script>
    export default {
        data() {
            return {
                detalleProductos: [],                
                dialogTransferencia: false,
                transferencia: {}
            }
        },
        mounted() {
            console.log('Mounted')
        },
        methods: {
            showTransferencia(item){
                this.detalleProductos = [];
                this.transferencia = Object.assign({}, item);
                axios.get(`/producto-transferencias/${item.id}`)
                    .then(res => {
                        // console.log(res.data)
                        for (let i = 0; i < res.data.length; i++) {
                            
                            let vUnit = (res.data[i].detalleproductos.precio*0.17) + res.data[i].detalleproductos.precio;
                            let vTotal = res.data[i].cantidad * vUnit;

                           this.detalleProductos.push({
                               producto: res.data[i].productos.producto,
                               cantidad: res.data[i].cantidad,
                               precio: vUnit,
                               total: vTotal
                           })
                        }
                        // Swal.hideLoading() 
                        this.dialogTransferencia = true;
                    })
                    .catch(err => {
                        console.log(err)
                    })
            }
        },
    }
</script>