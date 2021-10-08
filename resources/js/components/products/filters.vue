<template>
    <b-form @submit="onSubmit">
        <b-row>
            <b-col lg="6" class="my-1">
                <b-form-group
                    label="Filter by name"
                    label-for="filter-input"
                    label-cols-sm="3"
                    label-align-sm="right"
                    label-size="sm"
                    class="mb-0"
                >
                    <b-input-group size="sm">
                        <b-form-input
                            id="filter-input"
                            v-model="filter.name"
                            type="search"
                            placeholder="Type to Search"
                        ></b-form-input>

                        <b-input-group-append>
                            <b-button :disabled="!filter.name" @click="filter.name = ''">Clear</b-button>
                        </b-input-group-append>
                    </b-input-group>
                </b-form-group>
            </b-col>

            <b-col lg="6" class="my-1">
                <b-form-group
                    label="Filter by status"
                    label-for="filter-input"
                    label-cols-sm="3"
                    label-align-sm="right"
                    label-size="sm"
                    class="mb-0"
                >
                    <b-input-group size="sm">
                        <b-form-radio v-model="filter.status" name="status" value="1">Active</b-form-radio>
                        <b-form-radio v-model="filter.status" name="status" value="0">Inactive</b-form-radio>

                        <b-input-group-append class="mx-5">
                            <b-button :disabled="!filter.status" @click="filter.status = ''">Clear</b-button>
                        </b-input-group-append>
                    </b-input-group>
                </b-form-group>
            </b-col>
        </b-row>

        <b-button type="submit" variant="primary">Search</b-button>
    </b-form>
</template>

<script>
export default {
    data() {
        return {
            filter: {
                name: null,
                code: null,
                status: null
            },
        }
    },

    methods: {
        onSubmit(event) {
            event.preventDefault()

            this.$emit('filter', this.filter)
        }
    }
}
</script>
