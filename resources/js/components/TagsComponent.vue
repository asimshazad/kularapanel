<template>
    <div>
        <tags-input element-id="tags"
                    v-model="selectedTags"
                    :existing-tags="getTags"
                    :typeahead="true"
                    @change="onChange"
        ></tags-input>
    </div>
</template>

<script>
    import VoerroTagsInput from '@voerro/vue-tagsinput';

    export default {
        name: "TagsComponent",
        components: {
            'tags-input': VoerroTagsInput
        },
        props: [
            'elementId',
            'defaultTags'
        ],
        mounted: function () {
            if (this.defaultTags)
                this.selectedTags = JSON.parse(this.defaultTags);
        },
        data() {
            return {
                inputId: this.elementId,
                selectedTags: [],
                tags: [],
            }
        },
        methods: {
            setSelectedTags() {
                this.selectedTags = this.defaultTags;
            },
            onTagAdded(slug) {
                console.log(`Додано тег: ${slug}`);
            },
            onChange(value) {
                if (value.length) {
                    axios
                        .get('/admin/tags/search/' + value)
                        .then(response => {
                            this.tags = response.data;

                        })
                }
            }
        },
        computed: {
            getTags: function () {
                return this.tags;
            }
        }
    }
</script>

<style scoped>

</style>
