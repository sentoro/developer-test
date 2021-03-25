<template>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">Table to CSV Generator</div>

                    <div class="card-body">
                        <div class="table-wrapper">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th v-for="column in columns">
                                        <input type="text"
                                               class="form-control"
                                               :value="column.key"
                                               @input="updateColumnKey(column, $event)"
                                        />
                                    </th>
                                    <th>
                                        Actions
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(row, index) in data">
                                    <td v-for="column in columns">
                                        <input type="text" class="form-control" v-model="row[column.key]"/>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger" @click="remove_row(index)">Remove</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-secondary" @click="add_row()">Add Row</button>
                        <button type="button" class="btn btn-secondary" @click="add_column()">Add Column</button>
                        <input type="text"
                               class="form-control"
                               placeholder="Enter name for a new column"
                               v-model="newColName"/>
                    </div>

                    <div class="card-footer text-right">
                        <button class="btn btn-primary" type="button" @click="submit()">Export</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        name: "CSVGenerator",

        data() {
            return {
                data: [
                    {
                        first_name: 'John',
                        last_name: 'Doe',
                        emailAddress: 'john.doe@example.com'
                    },
                    {
                        first_name: 'John',
                        last_name: 'Doe',
                        emailAddress: 'john.doe@example.com'
                    },

                ],
                columns: [
                    {key: 'first_name'},
                    {key: 'last_name'},
                    {key: 'emailAddress'},
                ],
                newColName: ''
            }
        },

        methods: {
            add_row() {
                const columns = this.columns.map(column => column.key);

                const newRow = columns.reduce((acc,curr) => (acc[curr]='',acc),{});

                this.data.push(newRow);
            },

            remove_row(row_index) {
                this.data = this.data.filter((item, index) => index !== row_index);
            },

            add_column() {
                const columnName = this.newColName;

                this.columns.push({key : columnName});

                this.data.map(item => {
                    item[columnName] = '';
                });
            },

            updateColumnKey(column, event) {
                let oldKey = column.key;

                let columnKeyExists = !!this.columns.find(column => column.key === event.target.value);

                column.key = event.target.value;

                if (columnKeyExists) {
                    column.key = event.target.value.substring(0, event.target.value.length - 1);
                    return;
                }

                this.data.forEach(
                    (row) => {
                        if (row[oldKey]) {
                            row[column.key] = row[oldKey];
                            delete row[oldKey];
                        }
                    }
                )
            },

            submit() {
                let columns = [];

                for (let value of this.columns) {
                  columns.push(value.key);
                }

                return axios.post('/api/csv-export', {'data' : this.data,
                                                      })
                      .then(response => {
                          let blob = new Blob([response.data], { type: 'text/csv' }),
                           url = window.URL.createObjectURL(blob)

                          window.open(url)
                      }).catch(response => {
                          console.log(response);
                      });
            }
        },

        watch: {
        }
    }
</script>

<style scoped>
    .table-wrapper {
        overflow-y: scroll;
    }

    td, th {
        min-width:180px;
    }
</style>
