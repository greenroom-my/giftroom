<template>
    <div class="container">
        <div class="row">
            <div class="col-10 offset-1 col-sm-6 offset-sm-3">
                <h4 class="text-primary">Register</h4>

                <div class="form-group">
                    <label>Name</label>
                    <input v-model="name"
                           class="form-control" autofocus>
                </div>
                <div class="form-group">
                    <label>Email address</label>
                    <input v-model="email"
                           type="email" class="form-control">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input v-model="password"
                           type="password" class="form-control">
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input v-model="passwordConfirm"
                           type="password" class="form-control">
                </div>
                <button @click="register"
                        class="btn btn-block btn-primary text-white">Register</button>
            </div>
        </div>
    </div>
</template>

<script>
    import API from '../classes/Api';
    let api = new API;

    export default {
        mounted() {
            VueBus.$emit('pageChange', {
                logo: true,
                menu: false
            });
        },
        data() {
            return {
                name: '',
                email: '',
                password: '',
                passwordConfirm: ''
            }
        },
        methods: {
            register() {
                if(this.name === '') {
                    this.$popup({
                        message         : 'Name is required',
                        color           : '#fff',
                        backgroundColor : '#f48fb1',
                        delay           : 6
                    });
                    return false;
                }

                if(this.email === '') {
                    this.$popup({
                        message         : 'Email is required',
                        color           : '#fff',
                        backgroundColor : '#f48fb1',
                        delay           : 6
                    });
                    return false;
                }

                if(this.password === '') {
                    this.$popup({
                        message         : 'Password is required',
                        color           : '#fff',
                        backgroundColor : '#f48fb1',
                        delay           : 6
                    });
                    return false;
                }

                if(this.password !== this.passwordConfirm) {
                    this.$popup({
                        message         : 'Password confirmation mismatch',
                        color           : '#fff',
                        backgroundColor : '#f48fb1',
                        delay           : 6
                    });
                    return false;
                }

                axios.post(api.getEndpointURL('authRegister'), {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password
                }).then(function(res) {
                    if(res.data.code === 200) {
                        VueBus.user = res.data.data;
                        if(Array.isArray(VueBus.user.rooms) && VueBus.user.rooms.length === 0)
                            this.$router.push('/first-time');
                        else
                            this.$router.push('/room-home');
                    }
                }.bind(this)).catch(function(err) {
                    this.$popup({
                        message         : err.response.data.userMessage,
                        color           : '#fff',
                        backgroundColor : '#f48fb1',
                        delay           : 10
                    });
                }.bind(this));
            }
        }
    }
</script>
