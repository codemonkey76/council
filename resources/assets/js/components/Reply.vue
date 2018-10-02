<template>
    <div :id="'reply-'+id" class="card my-3" :class="isBest?'border-success':''">
        <div class="card-header" :class="isBest?'panel-success':''">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profile/' + reply.owner.name" v-text="reply.owner.name"></a>
                    said <span v-text="ago"></span>...
                </h5>

                <div>
                    <favorite v-if="signedIn" :reply="reply"></favorite>
                </div>

            </div>
        </div>
        <div class="card-body">
            <div v-if="editing">
                <form @submit="update">
                    <div class="form-group">
                        <wysiwyg v-model="body"></wysiwyg>
                    </div>
                    <button class="btn btn-sm btn-primary">Update</button>
                    <button class="btn btn-sm btn-link" @click="editing = false" type="button">Cancel</button>
                </form>
            </div>
            <div v-else v-html="body"></div>
        </div>
        <div class="card-footer level" v-if="authorize('owns', reply) || authorize('owns', reply.thread)">
            <div v-if="authorize('owns', reply)">
                <button class="btn btn-secondary btn-sm mr-1" @click="editing = true">Edit</button>
                <button class="btn btn-danger btn-sm" @click="destroy">Delete</button>
            </div>
            <button class="btn btn-sm ml-auto" :class="isBest?'btn-primary':'btn-secondary'" @click="markBestReply"
                    v-show="authorize('owns', reply.thread)">Best Reply?
            </button>
        </div>
    </div>
</template>

<script>
    import Favorite from './Favorite';
    import moment from 'moment';

    export default {
        props: ['reply'],

        components: {Favorite},
        data() {
            return {
                id: this.reply.id,
                editing: false,
                body: this.reply.body,
                isBest: this.reply.isBest
            }
        },

        computed: {
            ago() {
                return moment(this.reply.created_at + "Z").fromNow();
            },
        },
        created() {
            window.events.$on('best-reply-selected', id => {
                this.isBest = (id === this.id)
            });
        },
        methods: {
            markBestReply() {
                axios.post('/replies/' + this.id + '/best');
                window.events.$emit('best-reply-selected', this.id);
            },
            update() {
                axios.patch('/replies/' + this.id, {
                    body: this.body
                })
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    });

                this.editing = false;

                flash('Updated!');
            },

            destroy() {
                axios.delete('/replies/' + this.id);
                this.$emit('deleted', this.id);
            }
        }
    }
</script>