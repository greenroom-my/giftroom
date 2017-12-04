<template>
    <div class="container">
        <div class="row">
            <div class="col-10 offset-1 col-sm-6 offset-sm-3">
                <h4>{{roomName}}</h4>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            if (VueEvent.user)
                VueEvent.$emit('pageChange', {
                    logo: true,
                    menu: true
                });
            console.log(VueEvent.user);
        },
        data() {
            return {
                roomName: VueEvent.room.name
            }
        }
    }
</script>
