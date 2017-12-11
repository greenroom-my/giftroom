<template>
    <div class="container">
        <div class="row">
            <div class="col-10 offset-1 col-sm-6 offset-sm-3">
                <h3>Create Room</h3>
                <div class="form-group mt-3">
                    <input class="form-control" placeholder="Room Name" v-model="name">
                </div>
                <div class="form-group">
                    <textarea class="form-control" rows="5" placeholder="Description" v-model="description">
                    </textarea>
                </div>
                <div class="form-group">
                    <label>Gift Budget</label>
                    <input type="number" class="form-control" placeholder="Amount" v-model="budget">
                </div>
                <div class="form-group">
                    <label>Party Date</label>
                    <input type="date" class="form-control" placeholder="The Date" v-model="date">
                </div>
                <div class="form-group">
                    <button @click="create" class="btn btn-block btn-primary">Create</button>
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
            if(!VueBus.user)
                this.$router.push('/');
        },
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
                    event_day: this.date
                }, {
                    headers: {'Authorization': VueBus.user.id},
                }).then(function(res) {
                    if(res.data.code === 200) {
                        VueBus.room = res.data.data;
                        VueBus.rooms.push(VueBus.room);
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
