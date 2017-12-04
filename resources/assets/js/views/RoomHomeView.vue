<template>
    <div class="container">
        <div class="row">
            <div class="col-10 offset-1 col-sm-6 offset-sm-3">
                <h1>My Room for {{userName}}</h1>
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
                userName: VueEvent.user.name
            }
        }
    }
</script>
