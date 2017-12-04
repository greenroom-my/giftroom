<template>
    <nav class="navbar navbar-expand-md navbar-dark bg-light mb-4">
        <div class="container-fluid">
            <div>
                <i v-if="menu == true"
                   @click="toggleMenu()"
                   class="text-white fa fa-2x fa-bars"></i>
                <div v-else></div>
            </div>
            <div>
                <router-link
                        v-if="logo == true"
                        to="/" tag="a" class="navbar-brand">
                    Giftroom
                </router-link>
                <div v-else></div>
            </div>
            <i class="text-white fa fa-2x fa-github"></i>
        </div>
    </nav>
</template>

<script>
    export default {
        props: ['menu','logo'],
        methods: {
            toggleMenu() {
                this.$parent.slideout.toggle();
            }
        }
    }
</script>