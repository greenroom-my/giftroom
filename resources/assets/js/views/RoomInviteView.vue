<template>
    <div class="container">
        <div class="row">
            <div class="col-10 offset-1 col-sm-6 offset-sm-3">
                <h3>Invite Friends</h3>
                <div class="form-group mt-3">
                    <input class="form-control" placeholder="Email" v-model="email">
                    <button class="btn btn-primary btn-block mt-1">Add</button>
                    <router-link to="/room-home" tag="button" class="btn btn-block btn-default">Done</router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import API from '../classes/Api';
    let api = new API;

    export default {
        mounted() {
            if (VueEvent.user)
                VueEvent.$emit('pageChange', {
                    logo: true,
                    menu: true
                });
        },
        data() {
            return {
                name: '',
                description: '',
                budget: '',
                date: ''
            }
        },

        methods: {
            create() {
                if(this.name === '') {
                    this.$popup({
                        message         : 'Room name is required',
                        color           : '#fff',
                        backgroundColor : '#f48fb1',
                        delay           : 6
                    });
                    return false;
                }

                axios.post(api.getEndpointURL('roomCreate'), {
                    name: this.name,
                    description: this.description,
                    budget: this.budget,
                    date: this.date
                }, {
                    headers: {'user_id': VueEvent.user.id},
                }).then(function(res) {
                    if(res.data.code === 200) {
                        VueEvent.room = res.data.data;
                        this.$router.push('/invite-friends');
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
