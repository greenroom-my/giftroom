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
                    <li class="mb-1 disabled btn btn-sm btn-outline-dark no-border-radius btn-block">Vin Lim &nbsp; <i class="fa fa-gift text-muted"></i> </li>
                    <li class="mb-1 disabled btn btn-sm btn-outline-dark no-border-radius btn-block">Lim Beng Hai &nbsp; <i class="fa fa-gift text-muted"></i> </li>
                    <li class="mb-1 disabled btn btn-sm btn-outline-dark no-border-radius btn-block">Tan Kooi Choo &nbsp; <i class="fa fa-gift text-muted"></i> </li>
                    <li class="mb-1 disabled btn btn-sm btn-outline-dark no-border-radius btn-block">Lim Wei Qi &nbsp; <i class="fa fa-gift text-muted"></i> </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
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
                name: VueBus.room.name,
                description: VueBus.room.description,
                budget: VueBus.room.budget,
                eventDate: VueBus.room.event_day
            }
        }
    }
</script>
