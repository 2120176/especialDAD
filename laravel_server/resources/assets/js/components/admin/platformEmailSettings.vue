<template>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-2">
                <h3><strong>Configure platform Email</strong></h3>
                <p>&nbsp;</p>
                <div  v-show="success" class="alert alert-success alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>{{ success }}</strong>
                </div>
                <form method="post" v-on:submit.prevent="submitForm">
                    <div class="form-group row ">
                        <label class="col-sm-2 col-form-label col-form-label-lg text-right" >Email*</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" placeholder="Platform email" v-model="settings.email" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label col-form-label-lg text-right">Host *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="Host" v-model="settings.host" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label col-form-label-lg text-right">Username *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="Host username" v-model="settings.username" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label col-form-label-lg text-right">Password *</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" placeholder="Host password" v-model="settings.password" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label col-form-label-lg text-right">Port *</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" placeholder="Port number" v-model="settings.port" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label col-form-label-lg text-right">Encryption</label>
                        <div class="col-sm-10">
                            <select v-model="settings.encryption" name="encryption" id="encryption"  class="form-control">
                                <option v-for="option in options" v-bind:value="option.value">
                                    {{ option.text }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="btn-group pull-right">
                        <button type="button" class="btn btn-primary" v-on:click="cancel">Cancel</button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</template>
<script>
    export default {
        data() {
            return {
                settings: {
                    email:'',
                    username:'',
                    password: '',
                    host: '',
                    port: '',
                    encryption: ''
                },
                options: [
                    { text: 'SSL', value: 'ssl' },
                    { text: 'TLS', value: 'tls' },
                    { text: 'None', value: '' }
                ],
                success: false
            }
        },
        methods: {

            submitForm()
            {
                const postData = {
                    email: this.settings.email,
                    username: this.settings.username,
                    password: this.settings.password,
                    host: this.settings.host,
                    port: this.settings.port,
                    encryption: this.settings.encryption,
                };
                console.log(postData);

                //postdata nao ta a funcionar por isso passei os parametros individualmente
                axios.post('/api/changePlatformEmail', {"email": this.settings.email, "username": this.settings.username, "password": this.settings.password,
                    "host": this.settings.host, "port": this.settings.port, "encryption": this.settings.encryption })
                    .then((response) => {
                        this.success = response.data.msg;
                    })
                    .catch((error) => {

                    });
            },

            cancel(){
                this.getEmailSettings();
                this.success = false;
            },

            getEmailSettings(){
                axios.get('api/getPlatformEmailSettings')
                    .then((response) => {
                        this.settings = response.data.data;
                })
            }

        },
        mounted(){
            this.getEmailSettings();
        }
    }
</script>
<style></style>