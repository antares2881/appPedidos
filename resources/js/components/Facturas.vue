<template>
    <div class="text-center" >
        <v-dialog
            v-model="dialogFactura"
            width="800"
            persistent
        >
            <v-card 
            id="factura">
                <v-card-title>

                </v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col cols="12" md="6">
                            <h3><strong>{{datosFactura.mayoristas.nombres}} {{datosFactura.mayoristas.apellidos}}</strong></h3>
                            <h4><strong>Agente comercial</strong></h4>
                            <h5><strong>Nit: </strong>{{`${datosFactura.mayoristas.nit} ${datosFactura.mayoristas.dv}`}} </h5>
                            <h6><strong>Tel. </strong>{{datosFactura.mayoristas.telefono}}</h6>
                        </v-col>
                        <v-col cols="12" md="6">
                            <h3><strong>{{datosCliente.razon_social}}</strong></h3>
                            <h4><strong>Nit: </strong>{{datosCliente.nit}} - {{datosCliente.dv}}</h4>
                            <h6>{{datosCliente.municipios.mcpio}} - {{datosCliente.departamentos.nombre_dpto}}</h6>
                            <h6><strong>Direccion: </strong>{{datosCliente.direccion}}</h6>
                            <h6><strong>Telefono: </strong>{{datosCliente.telefono}}</h6>
                        </v-col>
                        <v-col cols="12" md="6">
                            <h6><strong>No. Factura: </strong></h6>
                            <v-text-field
                                type="number"
                                v-model="datosFactura.numero_factura"
                            ></v-text-field>
                            <p class="text-danger">{{erroresValidacion.numero_factura}}</p>
                        </v-col>
                        <v-col cols="12" md="4">
                            <h6>Fecha</h6>
                            <v-text-field 
                                v-model="datosFactura.fecha_factura"
                                type="date"
                            >
                            </v-text-field>
                            <p class="text-danger">{{erroresValidacion.fecha_factura}}</p>
                        </v-col>
                        <v-col cols="12" md="2">
                            <h6>Hora</h6>
                            <v-text-field 
                                type="time" 
                                v-model="datosFactura.hora_factura"
                            ></v-text-field>
                            <p class="text-danger">{{erroresValidacion.hora_factura}}</p>
                        </v-col>
                        <v-col cols="12" md="6">
                            <h6>Fecha vencimiento</h6>
                            <v-text-field 
                                v-model="datosFactura.fecha_vencimiento"
                                type="date"
                            >
                            </v-text-field>
                            <p class="text-danger">{{erroresValidacion.fecha_vencimiento}}</p>
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
                    <v-row class="mt-3">
                        <v-col cols="12" md="6">
                            <h6>Forma de pago</h6>
                            <v-select 
                                v-model="datosFactura.formapago_id"
                                :items="formaspago"
                            ></v-select>
                            <p class="text-danger">{{erroresValidacion.formapago_id}}</p>
                        </v-col>
                        <v-col cols="12" md="6">
                            <h6>Medio de pago</h6>
                            <v-select 
                                v-model="datosFactura.mediopago_id"
                                :items="mediospago"
                            ></v-select>
                            <p class="text-danger">{{erroresValidacion.mediopago_id}}</p>
                        </v-col>
                    </v-row>
                    <h4><strong>Total factura: </strong>{{datosFactura.total | currency}}</h4>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="primary"
                        @click="guardarFactura"
                        dark
                    >Facturar</v-btn>
                    <button
                        type="button"
                        class="btn btn-success"
                        @click="imprimir()"
                    >Imprimir</button>
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
                contadorErrores: 0,
                dialogFactura: false,
                datosCliente: {municipios: {}, departamentos: {}},
                datosFactura: {mayoristas: {}, productos: [], formapago_id: '', mediopago_id: '', numero_factura: '', fecha_vencimiento: '', fecha_factura: '', hora_factura: '', },
                erroresValidacion: {numero_factura: '', fecha_factura: ''},
                mediospago: [
                    {text: 'Efectivo', value: 1 },
                    {text: 'Tarjeta debito', value: 2 },
                    {text: 'Tarjeta de credito', value: 3 },
                    {text: 'Transferencia electronica', value: 4 },
                ],
                formaspago: [
                    {text: 'Contado', value: 1},
                    {text: 'Credito', value: 2}
                ]
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
            guardarFactura(){
                Swal.showLoading()
                this.validaciones();
                if(this.contadorErrores > 0)return;

                axios.post('/facturas', this.datosFactura)
                    .then(res => {
                        console.log(res.data)
                        if(res.data === 'ok'){
                             Swal.fire({
                                icon: 'success',
                                title: 'Guardado',
                                text: 'Factura guardada con exito',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                confirmButtonText: 'Aceptar'
                            }).then((result) => {
                                if(result.value){
                                    location.reload();
                                }
                            })
                        }else{
                            this.erroresValidacion = res.data;
                        }
                    })
            },    
            imprimir(){
                let printContents = document.getElementById('factura').innerHTML;
                let w = window.open();
                w.document.write(printContents);
                w.document.close(); // necessary for IE >= 10
                w.focus(); // necessary for IE >= 10
                w.print();
                w.close();
                return true;
            },    
            validaciones(){
                this.erroresValidacion = {};
                if (this.datosFactura.numero_factura === '') {
                    this.erroresValidacion.numero_factura = 'El numero de factura es obligatorio'
                    this.contadorErrores ++;
                }
                if (this.datosFactura.fecha_factura === '') {
                    this.erroresValidacion.fecha_factura = 'La fecha de factura es obligatoria'
                    this.contadorErrores ++;
                }
                if (this.datosFactura.hora_factura === '') {
                    this.erroresValidacionhora_factura = 'La hora de factura es obligatoria'
                    this.contadorErrores ++;
                }
                if (this.datosFactura.fecha_vencimiento === '') {
                    this.erroresValidacion.fecha_vencimiento = 'La fecha de vencimiento de la factura es obligatoria'
                    this.contadorErrores ++;
                }
                if (this.datosFactura.formapago_id === '') {
                    this.erroresValidacion.formapago_id = 'La forma de pago es obligatoria'
                    this.contadorErrores ++;
                }
                if (this.datosFactura.mediopago_id === '') {
                    this.erroresValidacion.mediopago_id = 'El medio de pago es obligatorio'
                    this.contadorErrores ++;
                }

            }
        },
    }
</script>