<template>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">Table to CSV Generator</div>

                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th></th>
                                <th v-for="column in columns">
                                    <input type="text"
                                           class="form-control"
                                           :value="column.key"
                                           @input="updateColumnKey(column, $event)"
                                    />
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(row, index) in tableItems">
                                <td>
                                    <b-button
                                        class="btn btn-danger btn-sm"
                                        @click="removeRow(index)"
                                    >
                                        Remove
                                    </b-button>
                                </td>
                                <td v-for="column in columns">
                                    <input type="text" class="form-control" v-model="row[column.key]">
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <div class="row">
                            <div class="col-md-2 offset-8">
                                <span
                                    class="d-inline-block"
                                    v-b-tooltip.left
                                    title="Change the default column name"
                                    tabindex="0"
                                >
                                    <button
                                        type="button"
                                        class="btn btn-secondary"
                                        @click="addColumn"
                                        :disabled="isAddColumnDisabled"
                                    >
                                        Add Column
                                    </button>
                                </span>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-secondary" @click="addRow">Add Row</button>
                            </div>
                        </div>

                    </div>

                    <div class="card-footer text-right">
                        <b-button variant="primary" type="button" @click="submit()">Export</b-button>
                        <b-toast
                            id="validation-toast"
                            variant="danger"
                            title="Validation error"
                            no-auto-hide
                            toaster="b-toaster-bottom-full"
                        >
                            <span class="danger" v-if="errors.tableItems">At least one row required</span>
                        </b-toast>
                    </div>
                </div>
            </div>
        </div>
        <v-dialog />
    </div>
</template>

<script>
export default {
    name: "CSVGenerator",

    data() {
        return {
            newColumnName: 'newColumn',
            tableItems: [
                {
                    firstName: 'John',
                    lastName: 'Doe',
                    email: 'john.doe@example.com'
                },
                {
                    firstName: 'John',
                    lastName: 'Doe',
                    email: 'john.doe@example.com'
                },

            ],
            columns: [
                {key: 'firstName'},
                {key: 'lastName'},
                {key: 'email'},
            ],
            errors: {}
        }
    },

    methods: {
        columnKeyExists(newValue) {
            return !!this.columns.find(column => column.key === newValue)
        },

        addRow() {
            let emptyItem = {}
            this.columns.forEach(item => emptyItem[item.key] = '')
            this.tableItems.push(emptyItem)
        },

        removeRow(rowIndex) {
            this.$modal.show('dialog', {
                title: 'This will remove the row, continue ?',
                buttons: [
                    {
                        title: 'Cancel',
                        handler: () => {
                            this.$modal.hide('dialog')
                        }
                    },
                    {
                        title: 'Remove',
                        handler: () => {
                            this.tableItems = this.tableItems.filter((item, index) => index !== rowIndex)
                            this.$modal.hide('dialog')
                        }
                    }
                ]
            })
        },

        addColumn() {
            this.columns.push({key: this.newColumnName})
            this.tableItems = this.tableItems.map(item => {
                item[this.newColumnName] = ''
                return item
            })
        },

        updateColumnKey(column, event) {
            let oldKey = column.key;
            let columnKeyExists = !!this.columns.find(column => column.key === event.target.value);

            column.key = event.target.value;

            if (columnKeyExists) {
                column.key = event.target.value.substring(0, event.target.value.length - 1);
                return;
            }

            this.tableItems = this.tableItems.map(item => {
                let newItem = {}
                delete Object.assign(newItem, item, {[column.key]: item[oldKey]})[oldKey]
                return newItem;
            })
        },

        submit() {
            console.log(this.tableItems)
            axios
                .post('/api/csv-export', {'tableItems': this.tableItems})
                .then(response => {
                    const url = window.URL.createObjectURL(
                        new Blob([response.data], { type: 'text/csv'})
                    );

                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'tableItems.csv');
                    document.body.appendChild(link);

                    link.click();
                })
                .catch(error => {
                    // I know this is not really good :)
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
                        this.$bvToast.show('validation-toast')
                    }
                    console.error(error)
                })
        }
    },

    computed: {
        isAddColumnDisabled() {
            return this.columns.some(column => column.key === this.newColumnName)
        },
    },

    watch: {
        //
    }
}
</script>

<style scoped>

</style>
