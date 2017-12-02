<template>
    <div class="container">
        <div class="home-logo text-center mt-5">
            <span>Giftroom</span>
        </div>

        <div class="col-10 offset-1 col-sm-6 offset-sm-3">
            <div class="small text-center">
                <p>Giftroom helps you organise your gift exchange party including invites, matches & wishlist.</p>
            </div>

            <div class="form-group mt-5">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
            </div>
            <button class="btn btn-block btn-primary">Login</button>
            <router-link to="/register" tag="button" class="btn btn-block btn-default">Register</router-link>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log(this.$router.currentRoute);
        }
    }
</script>
