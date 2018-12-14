<template>
    <div class="container">
        <div class="home-logo text-center mt-3">
            <span>Giftroom</span>
        </div>

        <div class="row">
            <div class="col-10 offset-1 col-sm-6 offset-sm-3">
                <div class="small text-center">
                    <p>Giftroom helps you organise your gift exchange party including invites, matches & wishlist.</p>
                </div>

                <div class="form-group mt-3">
                    <label>Email address</label>
                    <input type="email" class="form-control" placeholder="Enter email" v-model="email">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control" placeholder="Password" v-model="password">
                </div>
                <button @click="login" class="btn btn-block btn-primary text-white">Login</button>
                <router-link to="/register" tag="button" class="btn btn-block btn-default">Register</router-link>
            </div>
        </div>
    </div>
</template>

<script>
  import API from '../classes/Api';

  let api = new API;

  export default {
    mounted() {
      VueBus.$emit('pageChange', {
        logo: false,
        menu: false
      });

      this.persistentLogin();
    },
    data() {
      return {
        email: '',
        password: ''
      }
    },
    methods: {
      persistentLogin() {
        let persistentSession = this.retrieveSession();
        if (persistentSession.user) {
          VueBus.user = persistentSession.user;

          if (Array.isArray(persistentSession.rooms) && persistentSession.rooms.length === 0) {
            this.$router.push('/first-time');
          } else {
            VueBus.rooms = persistentSession.rooms;
            VueBus.room = persistentSession.room;

            this.$router.push('/room-home');
          }
        }
      },

      login() {
        if (this.email === '') {
          this.$popup({
            message: 'Email is required',
            color: '#fff',
            backgroundColor: '#f48fb1',
            delay: 6
          });
          return false;
        }

        if (this.password === '') {
          this.$popup({
            message: 'Password is required',
            color: '#fff',
            backgroundColor: '#f48fb1',
            delay: 6
          });
          return false;
        }

        axios.post(api.getEndpointURL('authLogin'), {
          email: this.email,
          password: this.password
        }).then(function (res) {
          if (res.data.code === 200) {
            VueBus.user = res.data.data;

            if (Array.isArray(VueBus.user.rooms) && VueBus.user.rooms.length === 0) {
              this.$router.push('/first-time');
            } else {
              VueBus.rooms = res.data.data.rooms;
              VueBus.room = res.data.data.rooms[0];

              this.storeSession(res.data.data, res.data.data.rooms[0], VueBus.rooms = res.data.data.rooms);
              this.$router.push('/room-home');
            }
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

      storeSession(user, room, rooms) {
        try {
          localStorage.setItem('user', JSON.stringify(user));
          localStorage.setItem('room', JSON.stringify(room));
          localStorage.setItem('rooms', JSON.stringify(rooms));
        } catch (e) {
          throw e;
        }
      },

      retrieveSession() {
        try {
          let user = JSON.parse(localStorage.getItem('user')) || null;
          let room = JSON.parse(localStorage.getItem('room')) || null;
          let rooms = JSON.parse(localStorage.getItem('rooms')) || null;

          return {
            user: user,
            room: room,
            rooms: rooms
          }
        } catch (e) {
          throw e;
        }
      }
    }
  }
</script>
