<template>
    <div class="container">
        <div class="row">
            <div class="col-10 offset-1 col-sm-6 offset-sm-3">
                <h4 class="text-primary">My Wish List</h4>
                <div class="form-group">
                    <textarea v-model="wishlists[0].description" class="form-control wish-list-item" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <textarea v-model="wishlists[1].description" class="form-control wish-list-item" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <textarea v-model="wishlists[2].description" class="form-control wish-list-item" rows="3"></textarea>
                </div>
                <button @click="save()"
                        class="btn btn-primary btn-block text-white">Save
                </button>
                <p v-if="isSaved" class="w-100 text-center small text-success">Updated successfully</p>
            </div>
        </div>
    </div>
</template>

<script>
    import API from '../classes/Api';
    let api = new API;

    export default {
        mounted() {
            if (VueBus.user)
                VueBus.$emit('pageChange', {
                    logo: true,
                    menu: true
                });

            this.get();
        },
        data() {
            return {
                isSaved: false,
            }
        },

        computed: {
            wishlists() {
                if(VueBus.room.wishlists.length === 0) {
                    return [{description: ''}, {description: ''}, {description: ''}];
                } else {
                    return VueBus.room.wishlists;
                }
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

            save() {
                axios.post(api.getEndpointURL('wishList', [{
                    name: VueBus.room.name
                }]), {
                    wishlists: [
                        {description: this.wishlists[0].description},
                        {description: this.wishlists[1].description},
                        {description: this.wishlists[2].description}
                    ]
                }, {
                    headers: {'Authorization': VueBus.user.id},
                }).then(function (res) {
                    if (res.data.code === 200) {
                        this.showSaved();
                    }
                }.bind(this)).catch(function (err) {
                    this.$popup({
                        message: err.response.data.userMessage,
                        color: '#fff',
                        backgroundColor: '#f48fb1',
                        delay: 10
                    });
                }.bind(this));
            },

            showSaved() {
                this.isSaved = true;
                setTimeout(function() {
                    this.isSaved = false;
                }, 1000);
            },
        }
    }
</script>
