<template>
    <v-app>
        <div class="container-fluid  dashboard-content"> 
            <v-dialog
                v-model="dialogAbonar"
                max-width="800"
                persistent
            >
                <v-card>
                    <v-card-title>
                        <h3>{{abono.cliente}}</h3>
                        
                    </v-card-title>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12">
                                <h4><strong>No. pedido: </strong>{{abono.num_pedido}}</h4>
                            </v-col>
                            <v-col cols="12" md="6">
                                <h4>Fecha Abono: </h4>
                                <v-text-field
                                    type="date"
                                    v-model="abono.fechaAbono"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="6">
                                <h4>Valor abono: </h4>
                                <v-text-field
                                    type="number"
                                    v-model.number="abono.maximo"
                                    :max="abono.maximo"
                                    min="0"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <h4>Observaciones: </h4>
                                <v-text-field 
                                    v-model="abono.observacion"
                                >
                                </v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12">
                                {{error}}
                            </v-col>
                        </v-row>
                    </v-card-text>
                    <v-card-actions class="justify-end">
                        <v-btn
                            color="primary"
                            dark
                            @click="saveAbono"
                        >Guardar</v-btn>
                        <v-btn
                            color="secondary"
                            dark
                            @click="dialogAbonar = false"
                        >Cancelar</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <v-card>
                <v-card-title>
                Pedidos calox
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
                :items="pedidosCalox"
                :search="search"
                >
                    <template v-slot:item.cobros="{item}">
                        <ul>
                            <li v-for="(i, index) in item.cobros" :key="index">
                                {{i.valor | currency}}                                
                            </li>
                        </ul>
                        <p><strong>Total:</strong> {{item.total | currency}}</p>
                    </template>
                    <template v-slot:item.valor="{item}">
                        <span :class="{'text-danger': item.total < item.valor, 'text-success': item.valor === item.total}">{{item.valor | currency}}</span> 
                    </template>
                    <template v-slot:item.tiempo="{item}">
                        <v-progress-linear
                        height="25"
                        :class="{'amarillo': item.tiempo > 30 && item.tiempo <= 45, 'rojo': item.tiempo > 45}"
                        >
                            <strong>{{ item.tiempo }}</strong>
                        </v-progress-linear>
                    </template>
                    <template v-slot:item.btnAbonar="{item}">
                        <v-btn
                            outlined
                            color="indigo"
                            @click="addAbono(item)"
                            small
                        >
                            <v-icon>
                                mdi-currency-usd
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
        props: ['ventas'],
        data() {
            return {
                abono: {},
                dialogAbonar: false,
                error: '',
                headers: [
                    {text: 'Cliente', value: 'cliente'},
                    {text: 'No. pedido', value: 'num_pedido'},
                    {text: 'Fecha', value: 'fecha'},
                    {text: 'Pagos', value: 'cobros'},
                    {text: 'Valor', value: 'valor'},
                    {text: 'Dias', value: 'tiempo'},
                    {text: 'Abonar', value: 'btnAbonar'}
                ],
                mensajeError: false,
                pedidosCalox: [],
                search: ''
            }
        },
        mounted() {
            this.setVentas()
        },
        methods: {
            addAbono(obj){
                console.log(obj)
                this.abono = Object.assign({}, obj);
                this.abono.maximo = this.abono.valor - this.abono.total;
                this.dialogAbonar = true;
            },
            saveAbono(){
                console.log(this.abono)
                if (this.abono.fechaAbono === '' || this.abono.maximo === '' || this.abono.observacion === '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Atencion',
                        text: 'Hay campos vacios'
                    });
                    return;
                }

                axios.post('/abono-pedidos', this.abono)
                    .then(res => {
                        if (res.data === 'ok') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Guardado',
                                text: 'El abono fue registrado'
                            });
                        }else{
                            this.error = res.data;
                        }
                    })
                    .catch(err => {
                        console.log(err)
                    })
            },
            setVentas(){
                console.log(this.ventas)
                let hoy = new Date();

                this.ventas.map((el) => {

                    let fecha = new Date(el.fecha); //Calcula el tiempo transcurrido de la factura
                    let tiempo = Math.round((hoy.getTime() - fecha.getTime())/86400000);

                    let total = 0; //Calcula el total de los abonos
                    for (let i = 0; i < el.cobros.length; i++) {
                        total += el.cobro[i].valor;                      
                    }

                    this.pedidosCalox.push({
                        id: el.id,
                        cliente: el.clientes.razon_social+' - '+el.municipios.mcpio,
                        num_pedido: el.num_pedido,
                        fecha: el.fecha,
                        valor: el.valor,
                        tiempo: tiempo,
                        total: total
                    })
                    
                })
            }
        },
    }
</script>
<style scoped>
    .amarillo{
        background: rgb(255, 255, 0);
    }
    .rojo{
        background: red;
    }
</style>
