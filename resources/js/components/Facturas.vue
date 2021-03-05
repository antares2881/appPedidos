<template>
    <div class="text-center">
        <v-dialog
            v-model="dialogFactura"
            width="800"
            persistent
        >
            <v-card>
                <v-card-title>

                </v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col cols="12">
                            <h3><strong>{{datosFactura.mayoristas.nombres}} {{datosFactura.mayoristas.apellidos}}</strong></h3>
                            <h4><strong>Agente comercial</strong></h4>
                            <h5><strong>Nit: </strong>{{`${datosFactura.mayoristas.nit} ${datosFactura.mayoristas.dv}`}} </h5>
                            <h6><strong>Tel. </strong>{{datosFactura.mayoristas.telefono}}</h6>
                        </v-col>
                        <v-col cols="12" md="9">
                            <h6>{{datosCliente.razon_social}}</h6>
                            <h6>{{datosCliente.nit}} - {{datosCliente.dv}}</h6>
                            <h6>{{datosCliente.municipios.mcpio}}</h6>
                        </v-col>
                        <v-col cols="12" md="3">
                            <h6><strong>No. Factura: </strong></h6>
                            <v-text-field
                                type="number"
                                v-model="datosFactura.numero_factura"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="9">
                            <h6><strong>Direccion: </strong>{{datosCliente.direccion}}</h6>
                        </v-col>
                        <v-col cols="12" md="3">
                            <h6><strong>Telefono: </strong>{{datosCliente.telefono}}</h6>
                        </v-col>
                        <v-col cols="12" md="6">
                            <h6>Fecha factura</h6>
                            <v-text-field 
                                v-model="datosFactura.fechaFactura"
                                type="date"
                            >
                            </v-text-field>
                        </v-col>
                        <v-col cols="12" md="6">
                            <h6>Fecha vencimiento</h6>
                            <v-text-field 
                                v-model="datosFactura.fechaVence"
                                type="date"
                            >
                            </v-text-field>
                        </v-col>
                    </v-row>
                    <v-simple-table>
                        <template v-slot:default>
                        <thead>
                            <tr>
                                <th class="text-left">
                                    Cantidad
                                </th>
                                <th class="text-left">
                                    Producto
                                </th>
                                <th class="text-left">
                                    Vl. Unitario
                                </th>
                                <th class="text-left">
                                    Vl. Total
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                            v-for="item in datosFactura.pedidos"
                            :key="item.id"
                            >
                                <td>{{ item.cantidad }}</td>
                                <td>{{ item.producto }}</td>
                                <td>{{ item.precio | currency}}</td>
                                <td>{{ item.total | currency}}</td>
                            </tr>
                        </tbody>
                        </template>
                    </v-simple-table>
                    <h4><strong>Total factura: </strong>{{datosFactura.total | currency}}</h4>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="primary"
                        dark
                    >Facturar</v-btn>
                    <v-btn
                        color="secondary"
                        dark
                        @click="dialogFactura = false"
                    >Cancelar</v-btn>                   
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                dialogFactura: false,
                datosCliente: {municipios: {}},
                datosFactura: {mayoristas: {}, productos: []},
            }
        },
        mounted() {
        },
        methods: {
            confirmFactura(item){
                this.datosFactura = Object.assign({}, item);
                // console.log(this.datosFactura)
                axios.get(`/clientes/${item.cliente_id}`)
                    .then(res => {
                        // console.log(res.data)
                        this.datosCliente = Object.assign({}, res.data)
                        this.dialogFactura = true;
                    })
                    .catch(err => {
                        console.log(err)
                    })
            },
        },
    }
</script>