<template>
    <div class="container">
        <div class="row">
            <div class="col-10 offset-1 col-sm-6 offset-sm-3">
                <h4 class="text-primary">Invite Friends</h4>
                <div class="form-group mt-3">
                    <input class="form-control" placeholder="Email" v-model="email">
                    <button @click="invite()" class="btn btn-primary btn-block mt-1">Add</button>
                    <router-link to="/room-home" tag="button" class="btn btn-block btn-default">Done</router-link>
                </div>
                <div class="mt-3">
                    <ul class="room-member-list">
                        <li v-for="member in members"
                            @click="uninvite(member.email)"
                            class="mb-1 btn-outline-danger">
                            <i class="fa fa-check text-muted"></i> <i class="fa fa-gift text-muted"></i> &nbsp;
                            {{member.name}}
                        </li>
                        <li v-for="invite in invites"
                            @click="uninvite(invite.email)"
                            class="mb-1 btn-outline-danger">
                            <i class="fa fa-envelope text-muted"></i> &nbsp;
                            {{invite.email}}
                        </li>
                    </ul>
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
                email: '',
                members: VueBus.room.members,
                invites: VueBus.room.invites
            }
        },

        methods: {
            invite() {
                if (this.email && this.email.trim() !== '') {
                    axios.post(
                        api.getEndpointURL('roomInvite', [{
                            'name': VueBus.room.name
                        }]), {
                            email: this.email.toLowerCase()
                        }, {
                            headers: {'user_id': VueBus.user.id}
                        }).then(function (res) {
                        if (res.data.code === 200) {
                            if (res.data.data.name)
                                VueBus.room.members.push(res.data.data);
                            else
                                VueBus.room.invites.push(res.data.data);
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
            },

            uninvite(email) {
                console.log(axios);
                if (confirm(`Remove ${email} from room?`)) {
                    axios.delete(
                        api.getEndpointURL('roomInvite', [{
                            'name': VueBus.room.name
                        }]), {
                            email: email
                        }, {
                            headers: {'user_id': VueBus.user.id}
                        }).then(function (res) {
                        if (res.data.code === 200) {
                            console.log(res.data);
//                            if(res.data.data.name)
//                                VueBus.room.members.push(res.data.data);
//                            else
//                                VueBus.room.invites.push(res.data.data);
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
    }
</script>
