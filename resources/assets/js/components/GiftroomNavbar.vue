<template>
    <nav class="navbar navbar-expand-md navbar-dark bg-light mb-4">
        <div class="container-fluid">
            <div>
                <i v-if="menu == true" class="text-white fa fa-2x fa-bars"></i>
                <div v-else></div>
            </div>
            <div>
                <a v-if="logo == true" class="navbar-brand" href="#">Giftroom</a>
                <div v-else></div>
            </div>
            <i class="text-white fa fa-2x fa-github"></i>
        </div>
    </nav>
</template>

<script>
    export default {
        props: ['menu','logo']
    }
</script>