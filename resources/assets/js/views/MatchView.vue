<template>
    <div class="container">
        <div class="row">
            <div class="col-10 offset-1 col-sm-6 offset-sm-3">
                <div v-if="false">
                    <h4 class="text-primary">My Match</h4>
                    <p>Vin Lim</p>

                    <hr>
                    <h6>Wish List</h6>
                    <div class="form-group">
                        <textarea class="form-control wish-list-item" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control wish-list-item" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control wish-list-item" rows="3"></textarea>
                    </div>
                </div>
                <div class="text-center">
                    <h4 class="text-primary">Wait for it</h4>
                    <p>You host has not started the draw for matches yet. Stay tuned!</p>
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

        },
        data() {
            return {
            }
        },

        methods: {
            get() {
                axios.get(api.getEndpointURL('wishList', [{
                    name: VueBus.room.name
                }]), {
                    headers: {'Authorization': VueBus.user.id},
                }).then(function (res) {
                    if (res.data.code === 200 && res.data.data)
                        VueBus.room.wishlists = res.data.data;
                }.bind(this)).catch(function (err) {
                    this.$popup({
                        message: err.response.data.userMessage,
                        color: '#fff',
                        backgroundColor: '#f48fb1',
                        delay: 10
                    });
                }.bind(this));
            },
        }
    }
</script>
