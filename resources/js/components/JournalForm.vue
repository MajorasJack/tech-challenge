<template>
    <div>
        <h1 class="mb-6">Journals -> Add New Journal</h1>

        <div class="max-w-lg mx-auto">
            <div class="form-group">
                <label for="name">Date</label>
                <input type="date" id="date" class="form-control" v-model="journal.date">
            </div>
            <div class="form-group">
                <label for="text">Text</label>
                <input type="text" id="text" class="form-control" v-model="journal.text">
            </div>

            <div class="text-right">
                <a :href='`/clients/${this.client.id}`' class="btn btn-default">Cancel</a>
                <button @click="storeJournal" class="btn btn-primary">Create</button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'JournalForm',

    props: ['client'],

    data() {
        return {
            journal: {
                date: '',
                text: '',
            }
        }
    },

    methods: {
        storeJournal() {
            axios.post(`/clients/${this.client.id}/journals`, this.journal)
                .then((data) => window.location.href = data.data.url);
        }
    }
}
</script>
