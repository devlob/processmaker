<template>
    <b-container fluid>
        <products-filters @filter="filter"/>

        <b-table ref="products_table" caption-top
                 striped
                 hover
                 bordered
                 :busy.sync="isBusy"
                 :sort-by.sync="sortBy"
                 :sort-desc.sync="sortDesc"
                 :fields="fields"
                 :items="products">
            <template #table-busy>
                <div class="text-center text-danger my-2">
                    <b-spinner class="align-middle"></b-spinner>
                    <strong>Loading...</strong>
                </div>
            </template>

            <template #table-caption>
                Products
            </template>

            <template #cell(actions)="row">
                <b-button size="sm" @click="edit(row.item, row.index, $event.target)" class="mr-1">
                    Edit
                </b-button>
            </template>
        </b-table>

        <div>
            Sorting By: <b>{{ sortBy }}</b>, Sort Direction:
            <b>{{ sortDesc ? 'Descending' : 'Ascending' }}</b>
        </div>

        <b-pagination class="d-flex justify-content-center"
            v-model="currentPage"
            :total-rows="rows"
            :per-page="perPage"
            first-text="First"
            prev-text="Prev"
            next-text="Next"
            last-text="Last"
        ></b-pagination>

        <b-modal :id="editModal.id" :title="editModal.title" ok-only @hide="resetEditModal">
            <b-form @submit="onSubmit">
                <b-form-group label="Name" label-for="name">
                    <b-form-input
                        id="name"
                        v-model="form.name"
                        type="text"
                        placeholder="Enter product name"
                        required />
                </b-form-group>

                <b-form-group label="Description" label-for="description">
                    <b-form-textarea
                        id="description"
                        v-model="form.description"
                        placeholder="Enter product description..."
                        rows="3"
                        max-rows="6"
                        required />
                </b-form-group>

                <b-form-group label="Code" label-for="code">
                    <b-form-input
                        id="code"
                        v-model="form.code"
                        type="text"
                        placeholder="Enter product code"
                        required />
                </b-form-group>

                <b-form-group>
                    <b-form-radio v-model="form.status" name="status" value="1">Active</b-form-radio>
                    <b-form-radio v-model="form.status" name="status" value="0">Inactive</b-form-radio>
                </b-form-group>

                <b-button type="submit" variant="primary">Submit</b-button>
            </b-form>
        </b-modal>
    </b-container>
</template>

<script>
export default {
    data() {
        return {
            rows: null,
            perPage: null,
            currentPage: null,
            isBusy: false,
            sortBy: 'id',
            sortDesc: false,
            fields: [
                {key: 'id', sortable: true},
                {key: 'name', sortable: true},
                {key: 'description'},
                {key: 'code', sortable: true},
                {key: 'status', sortable: true},
                {key: 'created_at', sortable: true},
                {key: 'actions', label: 'Actions'}
            ],
            products: [],
            editModal: {
                id: 'edit-modal',
                title: null,
            },
            form: {
                id: null,
                name: null,
                description: null,
                code: null,
                status: null
            }
        }
    },

    mounted() {
        this.getProducts(1)
    },

    methods: {
        getProducts(page, filters = '') {
            let promise = axios.get(`/api/products?page=${page}&api_token=${window.api_token}${filters}`)

            return promise.then((data) => {
                this.products = data.data.data

                const pagination = data.data.meta
                this.perPage = pagination.per_page
                this.currentPage = pagination.current_page
                this.rows = pagination.total

                this.isBusy = false
            }).catch(error => {
                this.isBusy = false

                this.products = []
            })
        },

        edit(item, index, button) {
            this.editModal.title = `Edit ${item.name}`

            this.form.id = item.id
            this.form.name = item.name
            this.form.description = item.description
            this.form.code = item.code
            this.form.status = item.status

            this.$root.$emit('bv::show::modal', this.editModal.id, button)
        },

        resetEditModal() {
            this.editModal.title = null
        },

        onSubmit(event) {
            event.preventDefault()

            let promise = axios.put(`/api/products/${this.form.id}?api_token=${window.api_token}`, this.form)

            return promise.then((data) => {
                const index = this.products.findIndex(product => product.id === this.form.id)

                this.products[index] = data.data.data

                this.$refs.products_table.refresh()

                this.$bvModal.hide(this.editModal.id)
            }).catch(error => {
                this.isBusy = false
            })
        },

        filter(filters) {
            let params = ''

            for (const filter in filters) {
                params += `&${filter}=${filters[filter]}`
            }

            this.getProducts(this.currentPage, params)
        }
    },

    watch: {
        currentPage: {
            handler: function (value) {
                this.getProducts(value)
            }
        }
    }
}
</script>
