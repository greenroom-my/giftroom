<template>
    <div class="container">
        <div class="row">
            <div class="col-10 offset-1 col-sm-6 offset-sm-3">
                <p class="small text-center">
                    If some one gave your a unique room id, enter below here. Or your can create a new room</p>
                <input v-model="roomId" class="form-control mb-1 input-room-id" placeholder="Room ID">
                <button @click="join" class="btn btn-block btn-primary">Join Room</button>
                <hr>
                <router-link to="/room-create" tag="button" class="btn btn-block btn-default">Create Room</router-link>
            </div>
        </div>
    </div>
</template>

<script>
    import API from '../classes/Api';
    let api = new API;

    export default {
        created() {
            if (!VueBus.user)
                this.$router.push('/');
        },
        mounted() {
            VueBus.$emit('pageChange', {
                logo: true,
                menu: false
            });
        },
        data() {
            return {
                roomId: ''
            }
        },

        methods: {
            join() {
                axios.post(api.getEndpointURL('roomJoin', [{
                    'name': this.roomId
                }]), {}, {
                    headers: {'Authorization': VueBus.user.id},
                }).then(function (res) {
                    if (res.data.code === 200) {
                        VueBus.room = res.data.data;
                        this.$router.push('/room-home');
                    }
                }.bind(this)).catch(function (err) {
                    this.$popup({
                        message: err.response.data.userMessage,
                        color: '#fff',
                        backgroundColor: '#f48fb1',
                        delay: 10
                    });
                }.bind(this));
            }
        }
    }
</script>
