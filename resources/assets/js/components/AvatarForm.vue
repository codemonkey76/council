<template>
    <div>
        <div class="level mb-2">
            <img :src="avatar" width="50" height="50" class="mr-2">
            <h1 v-text="user.name"></h1>
        </div>
        <form v-if="canUpdate" method="POST" enctype="multipart/form-data">
            <image-upload name="avatar" @loaded="onLoad"></image-upload>
        </form>
    </div>
</template>

<script>
    import ImageUpload from './ImageUpload';

    export default {
        props: ['user'],

        components: {ImageUpload},

        data() {
            return {
                avatar: this.user.avatar_path
            };
        },

        computed: {
            canUpdate() {
                return this.authorize(user => user.id === this.user.id)
            }
        },

        methods: {
            onLoad(data) {
                this.avatar = data.src;
                this.persist(data.file);
            },

            persist(avatar) {
                let data = new FormData;
                data.append('avatar', avatar);
                axios.post(`/api/users/${this.user.name}/avatar`, data)
                    .then(() => flash('Avatar uploaded'));
            }
        }
    }
</script>