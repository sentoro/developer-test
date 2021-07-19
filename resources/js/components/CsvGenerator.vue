<template>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">Table to CSV Generator</div>

                    <div class="card-body">
                        <div class="mb-3">
                            <button
                                class="btn btn-primary"
                                type="button"
                                @click="addColumn"
                            >Add Column</button>
                            <button
                                class="btn btn-primary"
                                type="button"
                                :disabled="isTableEmpty"
                                @click="addRow"
                            >Add Row</button>
                        </div>

                        <div v-if="isTableEmpty" class="empty-text text-center py-3">There are no records to show</div>

                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th v-for="(headerItem, index) in header">
                                    <input type="text" class="form-control" v-model="header[index]"/>
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-for="(row, rowIndex ) in rows">
                                <td v-for="(rowItem, index) in row">
                                    <input type="text" class="form-control" v-model="row[index]"/>
                                </td>
                                <td>
                                    <button
                                        class="btn btn-danger"
                                        type="button"
                                        @click="removeRow(rowIndex)"
                                    >Delete</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer text-right">
                        <button class="btn btn-primary"
                                type="button"
                                @click="submit"
                                :disable="isTableEmpty"
                        >Export</button>
                    </div>
                </div>
            </div>
        </div>

        <Notification :is-error="errorData.isError" :message="errorData.message" :type="errorData.type"/>
    </div>
</template>

<script>
import Notification from './Notification';

export default {
    components: {Notification},

    data: () => ({
        header: [],
        rows: [],

        errorData: {
            type: '',
            isError: false,
            message: '',
        },
    }),

    computed: {
        isTableEmpty() {
            return !this.rows.length && !this.header.length;
        },
    },

    methods: {
        addRow() {
            const emptyRows = [];
            const countFields = this.rows.length
                ? this.rows[0].length
                : this.header.length;

            for (let i = 0; i < countFields; i++) {
                emptyRows.push('');
            }
            this.rows.push(emptyRows)
        },

        removeRow(index) {
            if (!this.header.length) {
                this.header.push('');
            }
            this.rows.splice(index, 1);
        },

        addColumn() {
            if (!this.header.length) {
                this.header.push('');
                this.rows.push(['']);

                return;
            }
            this.header.push('');
            this.rows.forEach(item => item.push(''));
        },

        showMessage(message, type) {
            Vue.set(this.errorData, 'type', type);
            Vue.set(this.errorData, 'message', message);
            Vue.set(this.errorData, 'isError', true);

            setTimeout(() => Vue.set(this.errorData, 'isError', false), 2500);
        },

        buildCsvFile(value) {
            const csvContent = `data:text/csv;charset=utf-8,${value}`;
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement('a');

            link.setAttribute('href', encodedUri);
            link.setAttribute('download', 'my_data.csv');
            document.body.appendChild(link);

            link.click();
        },

        async submit() {
            try {
                const response = await axios.post('/api/csv-export', {
                    header: [...this.header],
                    data: [...this.rows]
                });

                this.buildCsvFile(response.data);
                this.showMessage('csv file generated', 'success');
            } catch (e) {
                this.showMessage(e.response.data.message, 'danger');
            }
        },
    },
}
</script>
