<template>
    <div>
        <h1>{{ $t('page.tags')}}</h1>
        <form @submit.prevent="submitTag">
            <div class="field has-addons">
                <div class="control">
                    <input class="input" v-model="tag" type="text" :placeholder="$t('addNew')" required>
                </div>
                <div class="control">
                    <button type="submit" class="button is-success" v-bind:class="{'is-loading' : isSubmitting }">
                        {{ $t('add')}}
                    </button>
                </div>
            </div>
        </form>
        <table class="table is-striped">
            <thead>
            <tr>
                <th>{{ $t('misc.tag')}}</th>
                <th>{{ $t('popularity')}}</th>
                <th>{{ $t('date.created')}}</th>
                <th>{{ $t('misc.handle')}}</th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="tag in tags">
                    <td>{{ tag.name }}</td>
                    <td>{{ tag.timesUsed }}</td>
                    <td>{{ tag.createdAt }}</td>
                    <td>
                        <a class="button is-danger tooltip is-tooltip-top"
                              v-bind:class="{ 'is-loading' : isDeletingTag(tag) }"
                              :data-tooltip="$t('action.clickToDelete')"
                              @click="deleteTag(tag)">
                            {{ $t('action.delete')}}
                        </a>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th>{{ $t('misc.tag')}}</th>
                    <th>{{ $t('popularity')}}</th>
                    <th>{{ $t('date.created')}}</th>
                    <th>{{ $t('misc.handle')}}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</template>
<script>
    export default {
        name: "AdminTags",
        i18n: {
            messages: {
                en: {
                    addNew: 'Add a new tag',
                    add: 'Add tag',
                    popularity: 'Popularity',
                },
                no: {
                    addNew: 'Legg til ny emneknagg',
                    add: 'Legg til emneknagg',
                    popularity: 'Popularitet',
                }
            }
        },
        data() {
            return {
                isSubmitting: false,
                isDeleting: false,
                tag: "",
                tags: []
            }
        },
        created() {
            let me = this;
            Vue.axios.get('tags').then((response) => {
                response.data.data.forEach((tag) => {
                    me.addTag(tag);
                });
            }).catch(() => {
            });
        },
        methods: {
            addTag(tagData) {
                this.tags.push({
                    id: tagData.id,
                    name: tagData.name,
                    createdAt: tagData.created_at,
                    timesUsed: tagData.count
                });
            },
            submitTag() {
                let me = this;
                me.isSubmitting = true;
                Vue.axios.post('tags', { name: me.tag }).then(response => {
                    me.addTag(response.data.data);
                    me.tag = "";
                    me.isSubmitting = false;
                }).catch(() => {
                    me.isSubmitting = false;
                });
            },
            deleteTag(tag) {
                let me = this;
                me.isDeleting = tag;
                Vue.axios.post('tags/' + tag.id + '/delete').then(() => {
                    let index = me.tags.indexOf(tag);
                    if(index > -1) {
                        me.tags.splice(index, 1);
                    }
                    me.isDeleting= null;
                }).catch(() => {
                    me.isDeleting = null;
                    me.$store.dispatch(
                        "error",
                        me.$t(
                            'error.couldNotDelete',
                            [me.$t('misc.tag')]
                        )
                    );
                });
            },
            isDeletingTag(tag) {
                return (this.isDeleting === tag);
            }
        },
        components: {
        }
    }
</script>
