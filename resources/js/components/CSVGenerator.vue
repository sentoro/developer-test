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
                            <tr v-for="(row,key) in  data">
                                <td v-for="(dataColumn, columnName) in row">
                                    <input type="text" class="form-control" v-model="row[columnName]"/>
                                </td>
                                <td>
                                    <button @click="remove_row(key)" type="button" class="btn btn-secondary">Remove</button>
                                </td>
                            </tr>

                            </tbody>
                        </table>

                        <button @click="addColumn()" type="button" class="btn btn-secondary">Add Column</button>
                        <button @click="addRow()" type="button" class="btn btn-secondary">Add Row</button>
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
            columnUnique: 0,
            rowUnique: 0,
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

            ]
        }
    },

    methods: {
        addRow() {
            let columnNames = this.columns.map(item => item.key);

            let obj = {};

            columnNames.map(key => {
                this.rowUnique++;
                obj[key + this.rowUnique] = ''
            });

            this.data.push(obj);
        },

        remove_row(row_index) {
            this.data.splice(row_index, 1);
        },
        addColumn() {
            this.columns.push({key: ''});

            this.data.map(row => row['new_row' + this.columnUnique] = '');

            this.columnUnique++;
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
            let columnNames = this.columns.map(item => item.key);

            let obj = {};

            columnNames.map(key => {
                this.rowUnique++;
                obj[key + this.rowUnique] = key
            });
            let data = [...this.data]
            data.unshift(obj);
            axios.patch('/api/csv-export', data).then((response) => {
                    const url = response.data.url;

                    const link = document.createElement('a');

                    link.href = url;

                    link.setAttribute('download', 'export.csv');

                    document.body.appendChild(link);

                    link.click();
                }
            );

        }
    },

    watch: {}
}
</script>

<style scoped>

</style>
