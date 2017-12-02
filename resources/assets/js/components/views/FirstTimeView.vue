<template>
    <div class="container">
        <div class="row">
            <div class="col-10 offset-1 col-sm-6 offset-sm-3">
                <p class="small text-center">If some one gave your a unique room id, enter below here. Or your can create a new room</p>
                <input class="form-control mb-1 btn-room-id" placeholder="Room ID">
                <router-link to="/join-room" tag="button" class="btn btn-block btn-primary">Join Room</router-link>
                <hr>
                <router-link to="/create-room" tag="button" class="btn btn-block btn-default">Create Room</router-link>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            VueEvent.$emit('pageChange', {
                logo: true,
                menu: false
            });
        }
    }
</script>
