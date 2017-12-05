<template>
    <div class="container">
        <div class="row">
            <div class="col-10 offset-1 col-sm-6 offset-sm-3">
                <h3>My Wish List</h3>
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


        },
        data() {
            return {
                name: '',
                description: '',
                budget: '',
                date: ''
            }
        },

        methods: {
            create() {
                if(this.name === '') {
                    this.$popup({
                        message         : 'Room name is required',
                        color           : '#fff',
                        backgroundColor : '#f48fb1',
                        delay           : 6
                    });
                    return false;
                }

                if(this.description === '') {
                    this.$popup({
                        message         : 'Description is required',
                        color           : '#fff',
                        backgroundColor : '#f48fb1',
                        delay           : 6
                    });
                    return false;
                }

                axios.post(api.getEndpointURL('roomCreate'), {
                    name: this.name,
                    description: this.description,
                    budget: this.budget,
                    date: this.date
                }, {
                    headers: {'Authorization': VueBus.user.id},
                }).then(function(res) {
                    if(res.data.code === 200) {
                        VueBus.room = res.data.data;
                        this.$router.push('/room-invite');
                    }
                }.bind(this)).catch(function(err) {
                    this.$popup({
                        message         : err.response.data.userMessage,
                        color           : '#fff',
                        backgroundColor : '#f48fb1',
                        delay           : 10
                    });
                }.bind(this));
            }
        }
    }
</script>
