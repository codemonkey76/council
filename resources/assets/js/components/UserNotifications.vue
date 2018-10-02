<template>
    <li class="nav-item dropdown" v-if="notifications.length">
        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
            <i class="fas fa-bell"></i>
        </a>

        <div class="dropdown-menu" >
            <a
                    class="dropdown-item"
                    v-for="notification in notifications"
                    :href="notification.data.link"
                    v-text="notification.data.message"
                    @click="markAsRead(notification)">
            </a>
        </div>
    </li>
</template>

<script>
    export default {
        data() {
            return {
                notifications:false,
            }
        },
        created() {
            axios.get('/profiles/' + window.App.user.name + '/notifications')
                .then(response => this.notifications = response.data);
        },
        methods: {
            markAsRead(notification) {
                axios.delete('/profiles/' + window.App.user.name + '/notifications/' + notification.id);
            }
        }

    }
</script>
<style>
    .dropdown-toggle::after {
        display:none;
    }
</style>
