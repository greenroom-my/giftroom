<template>
    <div class="container">
        <div class="home-logo text-center mt-3">
            <span>Giftroom</span>
        </div>

        <div class="row">
            <div class="col-10 offset-1 col-sm-6 offset-sm-3">
                <div class="small text-center">
                    <p>Giftroom helps you organise your gift exchange party including invites, matches & wishlist.</p>
                </div>

                <div class="form-group mt-3">
                    <label>Email address</label>
                    <input type="email" class="form-control" placeholder="Enter email" v-model="email">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control"  placeholder="Password" v-model="password">
                </div>
                <button @click="login" class="btn btn-block btn-primary">Login</button>
                <router-link to="/register" tag="button" class="btn btn-block btn-default">Register</router-link>
            </div>
        </div>
    </div>
</template>

<script>
    import API from '../classes/Api';
    let api = new API;

    export default {
        mounted() {
            VueEvent.$emit('pageChange', {
                logo: false,
                menu: false
            });
        },

        data() {
            return {
                email: '',
                password: ''
            }
        },

        methods: {
            login() {
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

                axios.post(api.getEndpointURL('authLogin'), {
                    email: this.email,
                    password: this.password
                }).then(function(res) {
                    if(res.data.code === 200) {
                        VueEvent.user = res.data.data;
                        if(Array.isArray(VueEvent.user.rooms) && VueEvent.user.rooms.length === 0)
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
