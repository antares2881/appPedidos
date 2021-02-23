<template>
    <v-app>
        <div class="container-fluid  dashboard-content">
            <template>
                <v-row justify="center">
                    <v-dialog
                        v-model="dialogClient"
                        persistent
                        max-width="800px"
                        top="100px"
                    >                    
                        <v-card>
                            <v-card-title class="headline">
                                {{ tituloModalCliente }}
                            </v-card-title>
                            <v-card-text>
                                <v-row>
                                    <v-col 
                                        cols="12"
                                        md="12"
                                    >
                                        <model-select 
                                            :options="municipios"
                                            v-model="cliente.municipio_id"
                                            placeholder="Buscar Municipio"
                                        ></model-select> 
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-select
                                            :items="tipoClientes"
                                            label="Tipo de cliente"
                                            v-model="cliente.tipocliente_id"
                                        >
                                        </v-select>
                                    </v-col>
                                    <v-col
                                        cols="6"
                                        md="5"
                                    >
                                        <v-text-field
                                            v-model="cliente.nit"
                                            label="NIT"
                                            type="number"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col
                                        cols="6"
                                        md="1"
                                    >
                                        <v-text-field
                                            v-model="cliente.dv"
                                            label="DV"
                                            type="number"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        md="12"
                                    >
                                        <v-text-field
                                            v-model="cliente.razon_social"
                                            label="Razon Social"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        md="6"
                                    >
                                        <v-text-field
                                            v-model="cliente.direccion"
                                            label="Direccion"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        md="6"
                                    >
                                        <v-text-field
                                            v-model="cliente.telefono"
                                            label="Telefono"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="12"
                                    >
                                        <ul>
                                            <li v-for="(item, index) in erroresCliente" :key="index">
                                                <v-alert
                                                    dense
                                                    outlined
                                                    type="error"
                                                >
                                                    {{item}}
                                                </v-alert>
                                            </li>
                                        </ul>
                                    </v-col>
                                </v-row>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn
                                        color="secondary darken-1"
                                        outlined
                                        @click="dialogClient = false"
                                    >
                                        Cancelar
                                    </v-btn>
                                    <v-btn
                                        color="indigo darken-1"
                                        outlined
                                        v-if="editarCliente === 0"
                                        @click="updateCliente"
                                    >
                                        Actualizar
                                    </v-btn>
                                    <v-btn
                                        color="primary darken-1"
                                        outlined
                                        @click="saveCliente"
                                        v-else
                                    >
                                        Guardar
                                    </v-btn>
                                </v-card-actions>                       
                            </v-card-text>                            
                        </v-card>
                    </v-dialog>
                </v-row>
                <v-data-table
                    :headers="headers"
                    :items="usuarios"
                    :search="search"
                    sort-by="email"
                    class="elevation-1 mt-3"
                >
                    <template v-slot:top>
                        <v-toolbar
                            flat
                        >
                            <v-row class="buscador">
                                <v-col md="6" xs="12">
                                    <v-text-field
                                        v-model="search"
                                        append-icon="mdi-magnify"
                                        label="Buscar..."
                                        single-line
                                        hide-details
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                            <v-dialog
                                v-model="dialog"
                                max-width="800px"
                            >                                
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn
                                        color="primary"
                                        dark
                                        class="mb-2"
                                        v-bind="attrs"
                                        v-on="on"
                                    >
                                        Nuevo usuario
                                        <v-icon right>
                                            mdi-account
                                        </v-icon>
                                    </v-btn>
                                </template>
                                <v-card >
                                    <v-card-title class="headline">
                                        {{ formTitle }}
                                    </v-card-title>
                                    <v-card-text>
                                        <v-container>
                                            <v-row>
                                                <v-col
                                                    cols="12"
                                                    sm="6"
                                                    md="6"
                                                >
                                                    <v-select
                                                        :items="roles"
                                                        label="Role"
                                                        v-model="user.role_id"
                                                    ></v-select>
                                                </v-col>
                                                <v-col
                                                    cols="12"
                                                    sm="6"
                                                    md="6"
                                                >
                                                    <v-text-field
                                                        v-model="user.email"
                                                        label="Correo electronico"
                                                    ></v-text-field>
                                                </v-col>
                                                <v-col
                                                    cols="12"
                                                    sm="6"
                                                    md="6"
                                                >
                                                    <v-text-field
                                                        v-model="user.name"
                                                        label="Nombres"
                                                    ></v-text-field>
                                                </v-col>
                                                <v-col
                                                    cols="12"
                                                    sm="6"
                                                    md="6"
                                                    v-if="editedIndex === -1"
                                                >
                                                    <v-text-field
                                                        v-model="user.password"
                                                        label="Password"
                                                        type="password"
                                                    ></v-text-field>
                                                </v-col>
                                                <v-col
                                                    cols="12"
                                                    md="12"
                                                >
                                                    <ul>
                                                        <li v-for="(item, index) in erroresUser" :key="index">
                                                            <v-alert
                                                                dense
                                                                outlined
                                                                type="error"
                                                            >
                                                                {{item}}
                                                            </v-alert>
                                                        </li>
                                                    </ul>
                                                </v-col>
                                            </v-row>
                                            
                                        </v-container>
                                    </v-card-text>

                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            color="secondary darken-1"
                                            outlined
                                            @click="close"
                                        >
                                            Cancelar
                                        </v-btn>
                                        <v-btn v-if="editedIndex === 0" 
                                            color="indigo" 
                                            outlined
                                            @click="updateUser()"
                                        >
                                            Actualizar
                                        </v-btn>
                                        <v-btn
                                            v-else
                                            color="success darken-1"
                                            outlined
                                            @click="save"
                                        >
                                            Guardar
                                        </v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                        </v-toolbar>
                    </template>
                    <template v-slot:item.roles="{item}">
                        {{item.role_id === 1 ?'Admin':'Cliente'}}                        
                    </template>
                    <template v-slot:item.actions="{ item }">
                        <v-btn 
                            @click="editItem(item)"
                            color="primary"
                            dark
                            outlined
                        >
                            <v-icon>
                                mdi-pencil
                            </v-icon>
                        </v-btn>
                        <v-btn 
                            v-if="item.role_id === 2"
                            @click="editCliente(item)"
                            :color="(item.cliente_id === null)?'red':'success'"
                            dark
                            outlined
                        >
                            <v-icon>
                                mdi-account
                            </v-icon>
                        </v-btn>
                    </template>
                </v-data-table>
            </template>
        </div>
    </v-app>
</template>
<script>
    import { ModelSelect } from 'vue-search-select';
    export default {
        data: () => ({
            cliente: {id: '', user_id: '', nit: '', dv: '', municipio_id: '', tipocliente_id: '', razon_social: '', direccion: '', telefono: ''},
            dialog: false,
            dialogClient: false,
            editarCliente: -1,
            editedIndex: -1,
            erroresCliente: '',
            erroresUser: '',
            headers: [
                {
                text: 'Usuario',
                align: 'start',
                value: 'email',
                },
                { text: 'Nombres', value: 'name' },
                { text: 'Role', value: 'roles'},
                { text: 'Accion', value: 'actions' },
            ],
            municipios: [],
            roles: [
                {value: 1, text: 'Admin'},
                {value: 2, text: 'Cliente'}
            ],
            search: '',
            tipoClientes: [
                {value: 1, text: 'Indirecto'},
                {value: 2, text: 'Directo'}
            ],
            user: {id: '', role_id: '', email: '', name: '', password: '' },
            usuarios: [],
        }),

        computed: {
            formTitle () {
                return this.editedIndex === -1 ? 'Nuevo Usuario' : 'Editar Usuario'
            },

            tituloModalCliente(){
                return this.editarCliente === -1 ? 'Nuevo cliente' : 'Editar cliente' 
            }
        },   

        created(){
            this.initialize()
        },

        watch: {
            dialog (val) {
                val || this.close()
            },
            dialogClient (val) {
                val || this.close()
            }
        },

        methods: {
            getMunicipios(){
                axios.get('/municipios')
                    .then(res => {
                        this.municipios = [];
                        for (let i = 0; i < res.data.length; i++) {
                            this.municipios.push({
                                value: res.data[i].id,
                                text: res.data[i].mcpio
                            });                         
                        }
                    })
                    .catch(err => {
                        console.log(err)
                    })
            },
            initialize(){
                axios.get('/usuarios')
                    .then(res => {
                        this.usuarios = [];
                        for (let i = 0; i < res.data.length; i++) {
                            this.usuarios.push({
                                id: res.data[i].id,
                                email: res.data[i].email,
                                name: res.data[i].name,
                                role_id: res.data[i].role_id,
                                cliente_id: res.data[i].cliente_id,
                                nit: res.data[i].nit,
                                dv: res.data[i].dv,
                                municipio_id: res.data[i].municipio_id,
                                tipocliente_id: res.data[i].tipocliente_id,
                                razon_social: res.data[i].razon_social,
                                direccion: res.data[i].direccion,
                                telefono: res.data[i].telefono
                            })                        
                        }
                    })
                    .catch(err => {
                        console.log(err)
                    })
            },
            editCliente(item){

                if(item.cliente_id !== null){
                    this.editarCliente = 0;
                    this.cliente = Object.assign({}, item)
                }
                this.cliente.user_id = item.id;
                this.getMunicipios();
                this.dialogClient = true;

            },
            editItem(item){
                this.editedIndex = 0;  
                this.user = Object.assign({}, item) //Asigna el objeto item a el objeto usuario
                this.dialog = true
            },  
            close(){
                this.dialog = false;
                this.dialogClient = false;
                this.$nextTick(() => {
                    this.erroresCliente = '';
                    this.erroresUser = '';
                    this.user = {};
                    this.editarCliente = -1
                    this.editedIndex = -1
                })
            },
            save(){
                axios.post('/usuarios', this.user)
                    .then(res => {
                        if (res.data === 'ok') {
                            this.initialize();
                            this.close();                            
                        } else {
                            this.erroresUser = res.data
                        }
                    })
                    .catch(err => {
                        console.log(err)
                    })
            },
            saveCliente(){
                axios.post('/clientes', this.cliente)
                    .then(res => {
                        if(res.data === 'ok'){
                            this.initialize()
                            this.close()
                        }else{
                            this.erroresCliente = res.data;
                        }
                    })
                    .catch(err => {
                        console.log(err)
                    })
            },
            updateCliente(){
                axios.put(`/clientes/${this.cliente.cliente_id}`, this.cliente)
                    .then(res => {
                        if (res.data === 'ok') {
                            this.initialize()
                            this.close()
                        } else {
                            this.erroresCliente = res.data;
                        }
                    })
            },
            updateUser(){
                axios.put(`/usuarios/${this.user.id}`, this.user)
                    .then(res => {
                        if (res.data === 'ok') {                            
                            this.initialize();
                            this.close();
                        } else {
                            this.erroresUser = res.data
                        }
                    })
                    .catch(err => {
                        console.log(err)
                    })
            }
        },        
        components: {
            ModelSelect 
        }
    }
</script>
