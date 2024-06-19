<template>
    <div>
        <h1 class="mb-6">Clients -> {{ client.name }}</h1>

        <div class="flex">
            <div class="w-1/3 mr-5">
                <div class="w-full bg-white rounded p-4">
                    <h2>Client Info</h2>
                    <table>
                        <tbody>
                            <tr>
                                <th class="text-gray-600 pr-3">Name</th>
                                <td>{{ client.name }}</td>
                            </tr>
                            <tr>
                                <th class="text-gray-600 pr-3">Email</th>
                                <td>{{ client.email }}</td>
                            </tr>
                            <tr>
                                <th class="text-gray-600 pr-3">Phone</th>
                                <td>{{ client.phone }}</td>
                            </tr>
                            <tr>
                                <th class="text-gray-600 pr-3">Address</th>
                                <td>{{ client.address }}<br/>{{ client.postcode + ' ' + client.city }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="w-2/3">
                <div>
                    <button class="btn" :class="{'btn-primary': currentTab == 'bookings', 'btn-default': currentTab != 'bookings'}" @click="switchTab('bookings')">Bookings</button>
                    <button class="btn" :class="{'btn-primary': currentTab == 'journals', 'btn-default': currentTab != 'journals'}" @click="switchTab('journals')">Journals</button>
                </div>

                <!-- Bookings -->
                <div class="bg-white rounded p-4" v-if="currentTab == 'bookings'">
                    <div class="flow-root">
                        <h3 class="float-left mb-3">List of client bookings</h3>

                        <div class="float-right">
                            <div class="dropdown">
                                <button
                                    class="btn btn-info text-white dropdown-toggle"
                                    type="button"
                                    id="bookingsMenuButton"
                                    data-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                >
                                    All Bookings
                                </button>
                                <div class="dropdown-menu" aria-labelledby="bookingsMenuButton">
                                    <a class="dropdown-item" @click="filterBookings('all')">All bookings</a>
                                    <a class="dropdown-item" @click="filterBookings('past')">Past bookings only</a>
                                    <a class="dropdown-item" @click="filterBookings('future')">Future bookings only</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <template v-if="client.bookings && client.bookings.length > 0">
                        <table>
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Notes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="booking in client.bookings" :key="booking.id">
                                    <td>{{ booking.start }} to {{ booking.end }}</td>
                                    <td>{{ booking.notes }}</td>
                                    <td>
                                        <button class="btn btn-danger btn-sm" @click="deleteBooking(booking)">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </template>

                    <template v-else>
                        <p class="text-center">The client has no bookings.</p>
                    </template>

                </div>

                <!-- Journals -->
                <div class="bg-white rounded p-4" v-if="currentTab == 'journals'">
                    <div class="flow-root">
                        <h3 class="float-left mb-3">List of client journals</h3>

                        <a :href="`/clients/${client.id}/journals/create`" class="float-right btn btn-primary">+ New Journal</a>
                    </div>

                    <template v-if="journals && journals.length > 0">
                        <table>
                            <thead>
                            <tr>
                                <th>Time</th>
                                <th>Notes</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="journal in journals" :key="journal.id">
                                <td>{{ journal.date }}</td>
                                <td>{{ journal.text }}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm" @click="deleteJournal(journal)">Delete</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'ClientShow',

    props: ['client'],

    data() {
        return {
            currentTab: 'bookings',
            journals: [],
            journalsLoaded: false,
        }
    },

    mounted() {
        axios.get(`/clients/${this.client.id}/journals`).then((response) => this.journals = response.data)
        this.journalsLoaded = true;
    },

    methods: {
        switchTab(newTab) {
            this.currentTab = newTab;
        },

        deleteBooking(booking) {
            axios.delete(`/bookings/${booking.id}`);
        },

        deleteJournal(booking) {
            axios.delete(`/clients/${this.client.id}/journals/${booking.id}`);
        },

        filterBookings(filter) {
            window.location = `/clients/${this.client.id}?filter=${filter}`;
        }
    }
}
</script>
