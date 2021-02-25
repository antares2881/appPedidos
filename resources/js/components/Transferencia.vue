<template>
    <v-app>
        <div class="container-fluid  dashboard-content">  
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
            <v-card>
                <v-card-title>
                Transferencias
                <v-spacer></v-spacer>
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Filtar"
                    single-line
                    hide-details
                ></v-text-field>
                </v-card-title>
                <v-data-table
                :headers="headers"
                :items="transferencias"
                :search="search"
                >
                    <template v-slot:item.estado="{item}">
                        <v-chip
                            :color="getColor(item.estado)"
                            small
                            dark
                        >
                            {{ (item.estado) }}
                        </v-chip>
                    </template>
                    <template v-slot:item.valor="{item}">                        
                        {{ item.valor | currency }}
                    </template>
                    <template v-slot:item.botones="{item}">                        
                        
                        <v-btn
                            color="primary"
                            small
                            dark
                            @click="showTransferencia(item)"
                        >
                            <v-icon
                                dark
                            >
                                mdi-eye
                            </v-icon>                              
                        </v-btn>   
                        <v-btn
                            color="red"
                            dark
                            small
                            @click="deleteTransferencia(item.id)"
                            v-if="item.estado_id === 1"
                        >
                            <v-icon

                            >
                                mdi-minus-circle
                            </v-icon>
                        </v-btn>
                    </template>
                </v-data-table>
            </v-card>
        </div>
    </v-app>
</template>
<script>
    export default {
        data() {
            return {
                detalleProductos: [],
                dialogTransferencia: false,
                headers: [
                    {text: 'Cliente', value: 'cliente'},
                    {text: 'Numero de transferencia', value: 'numero'},
                    {text: 'Fecha', value: 'fecha'},
                    {text: 'Valor', value: 'valor'},
                    {text: 'Estado', value: 'estado'},
                    {text: 'Accion', value: 'botones'},
                ],
                search: '',
                transferencia: {},
                transferencias: []
            }
        },
        mounted() {
            this.getTransferencias()
        },
        methods: {  
            deleteTransferencia(id){
                Swal.fire({
                    title: 'Estas seguro?',
                    text: "Intentas cancelar una transferencia!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar'
                    }).then((result) => {
                    if (result.isConfirmed) {
                        axios.put(`/estado-transferencias/${id}`)
                            .then(res => {
                                // console.log(res.data)
                                if(res.data === 'ok'){
                                    this.getTransferencias();
                                    Swal.fire(
                                        'Cancelada!',
                                        'La transferencia fue cancelada.',
                                        'success'
                                    )
                                }
                            })
                            .catch(err => {
                                console.log(err)
                            })
                    }
                })
            }, 
            getColor(estado){
                if(estado === 'Pendiente') return 'info'
                else if(estado === 'Facturado') return 'success'
                else return 'red'
            },   
            getTransferencias(){
                axios.get('/transferencias')
                    .then(res => {
                        console.log(res.data)
                        for (let i = 0; i < res.data.length; i++) {
                            this.transferencias.push({
                                id: res.data[i].id,
                                nit: res.data[i].clientes.nit,
                                dv: res.data[i].clientes.dv,
                                cliente: res.data[i].clientes.razon_social,
                                estado: res.data[i].estados.estado,
                                estado_id: res.data[i].estado_id,
                                numero: res.data[i].numero,
                                fecha: res.data[i].fecha,
                                valor: res.data[i].valor
                            });
                        }
                    })
                    .catch(err => {
                        console.log(err)
                    })
            },
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
                        this.dialogTransferencia = true;
                    })
                    .catch(err => {
                        console.log(err)
                    })
            }
        },
    }
</script>