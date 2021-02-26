<template>
    <v-app>
        <div class="container-fluid  dashboard-content">  
            <printransfer-component ref="imprimir"/>
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
                            @click="showTransfer(item)"
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
                // dialogTransferencia: false,
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
            showTransfer(item){
                // console.log(item)
                this.$refs.imprimir.showTransferencia(item);
            }
            /* showTransferencia(item){
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
            } */
        },
    }
</script>