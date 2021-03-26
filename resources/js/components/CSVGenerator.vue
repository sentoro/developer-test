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
                            <tr v-for="row in data">
                                <td v-for="(dataColumn, columnName) in row">
                                    <input type="text" class="form-control" v-model="row[columnName]"/>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <button type="button" class="btn btn-secondary" @click="addColumn">Add Column</button>
                        <button type="button" class="btn btn-secondary" @click="addRow">Add Row</button>
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
                        firstName: 'John',
                        lastName: 'Doe',
                        emailAddress: 'john.doe@example.com'
                    },
                    {
                        firstName: 'John',
                        lastName: 'Doe',
                        emailAddress: 'john.doe@example.com'
                    },

                ],
                columns: [
                    {key: 'first name'},
                    {key: 'last name'},
                    {key: 'email address'},
                ]
            }
        },

        methods: {
            addRow() {
                let row = this.data[0];
                for (var item in row) {
                    item = "";
                }
                this.data.push(row)
            },

            removeRow(row_index) {
                // remove the given row
            },

            addColumn() {
                this.columns.push({key: ''})
                for (var item in this.data) {
                    console.log(this.data[item]);
                    var keyname = 'key' + this.columns.length;
                    this.data[item][keyname] = '';
                }
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
                axios.patch('/api/csv-export', {'columns': this.columns, 'data': this.data}).then((response) => {
                    var encodedUri = encodeURI(response.data);
                    console.log(encodedUri);
                    var link = document.createElement("a");
                    link.setAttribute("href", encodedUri);
                    link.setAttribute("download", "my_data.csv");
                    document.body.appendChild(link); // Required for FF

                    link.click()
                }).catch(() => console.log('error occured'));
            }
        },

        watch: {
        }
    }
</script>

<style scoped>

</style>
