<template>
    <div class="container">
        <div class="row">
            <div class="col-10 offset-1 col-sm-6 offset-sm-3">
                <p class="small text-center">If some one gave your a unique room id, enter below here. Or your can create a new room</p>
                <input class="form-control mb-1 input-room-id" placeholder="Room ID">
                <router-link to="/room-join" tag="button" class="btn btn-block btn-primary">Join Room</router-link>
                <hr>
                <router-link to="/room-create" tag="button" class="btn btn-block btn-default">Create Room</router-link>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            if(!VueBus.user)
                this.$router.push('/');

            VueBus.$emit('pageChange', {
                logo: true,
                menu: false
            });
        }
    }
</script>
