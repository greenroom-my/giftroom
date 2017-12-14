<template>
    <div class="container">
        <div class="row">
            <div class="col-10 offset-1 col-sm-6 offset-sm-3">
                <div v-if="loading" class="text-center hidden">
                    <h4 class="text-primary">Loading</h4>
                    <p>Loading your giftee. Please wait a moment.</p>
                </div>
                <div v-if="error" class="text-center hidden">
                    <h4 class="text-primary">Wait for it</h4>
                    <p>You host has not started the draw for matches yet. Stay tuned!</p>
                </div>
                <div v-if="match && wishlist">
                    <h4 class="text-primary">My Match</h4>
                    <p class="mb-0">{{matchName}}</p>
                    <small class="text-muted">{{matchEmail}}</small>
                    <hr>
                    <h6>Wish List</h6>
                    <div class="form-group">
                        <textarea v-model="wishlist1" class="form-control wish-list-item" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea v-model="wishlist2" class="form-control wish-list-item" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <textarea v-model="wishlist3" class="form-control wish-list-item" rows="3"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import API from '../classes/Api';
    let api = new API;

    export default {
        created() {
            if(!VueBus.room)
                this.$router.push('/');
        },
        mounted() {
            this.get();
        },
        data() {
            return {
                loading: true,
                error: false,
                match: false,
                wishlist: false,
                matchName: '',
                matchEmail: '',
                wishlist1: '',
                wishlist2: '',
                wishlist3: '',
            }
        },

        methods: {
            get() {
                axios.get(api.getEndpointURL('match', [{
                    name: VueBus.room.name
                }]), {
                    headers: {'Authorization': VueBus.user.id},
                }).then(function (res) {
                    if (res.data.code === 200 && res.data.data) {
                        this.loading = false;
                        if(res.data.data.match) {
                            this.match = true;
                            this.matchName = res.data.data.match.name;
                            this.matchEmail = res.data.data.match.email;
                        }
                        if(res.data.data.wishlist) {
                            this.wishlist = true;
                            this.wishlist1 =  res.data.data.wishlist[0].description;
                            this.wishlist2 =  res.data.data.wishlist[1].description;
                            this.wishlist3 =  res.data.data.wishlist[2].description;
                        }
                    }
                }.bind(this)).catch(function (err) {
                    this.loading = false;
                    this.error = true;
//                    this.$popup({
//                        message: err.response.data.userMessage,
//                        color: '#fff',
//                        backgroundColor: '#f48fb1',
//                        delay: 10
//                    });
                }.bind(this));
            },
        }
    }
</script>
