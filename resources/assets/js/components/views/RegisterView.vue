<template>
    <div class="container">
        <div class="col-10 offset-1 col-sm-6 offset-sm-3">
            <h2>Register</h2>

            <div class="form-group">
                <label for="">First Name</label>
                <input class="form-control" id="" aria-describedby="emailHelp" placeholder="Nama ape?   ">
            </div>
            <div class="form-group ">
                <label for="">Last Name</label>
                <input class="form-control" id="" aria-describedby="emailHelp" placeholder="Nama bapa?">
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                       placeholder="Enter email">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
            </div>
            <div class="form-group">
                <label for="confirmPassword">Confirm Password</label>
                <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm again">
            </div>
            <button class="btn btn-block btn-primary">Register</button>
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
