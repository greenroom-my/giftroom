<template>
    <div class="container">
        <div class="row">
            <div class="col-10 offset-1 col-sm-6 offset-sm-3">
                <p v-if="!description" class="no-margin">Welcome to</p>
                <h4 class="text-primary text-uppercase">{{name}}</h4>
                <p class="small">{{description}}</p>
                <div v-if="eventDate || budget" class="row">
                    <div v-if="eventDate" class="col-6">
                        <p class="small text-muted no-margin">Party Date</p>
                        <div class="btn btn-block btn-success">{{eventDate}}</div>
                    </div>
                    <div v-if="budget" class="col-6">
                        <p class="small text-muted no-margin">Gift Budget</p>
                        <div class="btn btn-block btn-success">{{budget}}</div>
                    </div>
                </div>
                <hr>
                <div class="row mb-1">
                    <div class="col-12">
                        <label>Members</label>
                        <router-link to="/room-invite" tag="button" class="btn btn-outline-default btn-sm float-right">
                            <i class="fa fa-plus"></i>
                        </router-link>
                    </div>
                </div>
                <ul class="room-member-list">
                    <li v-for="member in members"
                        class="mb-1">
                        <i class="fa fa-check text-muted"></i> <i class="fa fa-gift text-muted"></i> &nbsp;
                        {{member.name}}
                    </li>
                    <li v-for="invite in invites"
                        class="mb-1">
                        <i class="fa fa-envelope text-muted"></i> &nbsp;
                        {{invite.email}}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    import API from '../classes/Api';
    let api = new API;

    export default {
        mounted() {
            if(!VueBus.room)
                this.$router.push('/');

            if (VueBus.user)
                VueBus.$emit('pageChange', {
                    logo: true,
                    menu: true
                });

            this.room();
        },
        data() {
            return {
                name: VueBus.room.name,
                description: VueBus.room.description,
                budget: VueBus.room.budget,
                eventDate: VueBus.room.event_day,
                members: [],
                invites: []
            }
        },
        methods: {
            room() {
                axios.get(api.getEndpointURL('roomInfo', [{
                    'name': this.name
                }]), {
                    headers: {'Authorization': VueBus.user.id},
                }).then(function (res) {
                    if (res.data.code === 200) {
                        VueBus.room = res.data.data;
                        this.members = res.data.data.members;
                        this.invites = res.data.data.invites;
                    }
                }.bind(this)).catch(function (err) {
                    this.$popup({
                        message: err.response.data.userMessage,
                        color: '#fff',
                        backgroundColor: '#f48fb1',
                        delay: 10
                    });
                }.bind(this));
            }
        }
    }
</script>
