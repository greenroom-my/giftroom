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
    export default {
        mounted() {
            VueEvent.$emit('pageChange', {
                logo: false,
                menu: false
            });
        },

        data() {
            return {
                email: 'vin@greenroom.com.my',
                password: '111'
            }
        },

        methods: {
            login() {
                if(this.email === '') {
                    alert('No email');
                    return false;
                }

                if(this.password === '') {
                    alert('No password');
                    return false;
                }
                
                axios.post('http://127.0.0.1:8000/api/user/login', {
                    email: this.email,
                    password: this.password
                }).then(function(res) {
                    if(res.data.code)
                        this.$router.push('/room-home');

                }.bind(this)).catch(function(err) {
                    console.error(err);
                    this.$popup({
                        message         : err.response.data.userMessage,
                        color           : '#fff',
                        backgroundColor : '#f48fb1',
                        delay           : 10
                    })
                }.bind(this));
            }
        }


    }
</script>
