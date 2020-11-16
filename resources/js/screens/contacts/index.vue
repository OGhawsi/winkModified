<script type="text/ecmascript">
    import loadsEntries from '../loadsEntries';
    // import FiltersDropdown from '../../partials/FilterDropdown.vue';

    export default {
        mixins: [loadsEntries],


        components: {
            // 'filters': FiltersDropdown
        },


        /**
         * The component's data.
         */
        data() {
            return {
                baseURL: '/api/contact',
                entries: [],
                hasMoreEntries: false,
                nextPageUrl: null,
                loadingMoreEntries: false,
                ready: false,
                searchQuery: ''
            };
        },
        methods: {
            deleteAuthor(id) {
                this.alertConfirm("Are you sure you want to delete this author?", () => {
                    this.http().delete('/api/contact/' + id).then(response => {
                        this.$router.push({name: 'posts'})
                    }).catch(error => {
                        this.alertError(error.response.data.message);
                    });
                });
            }
        },


        /**
         * Prepare the component.
         */
        mounted() {
            document.title = "Writing Request — Science4All.";

            this.loadEntries();


           
        },
    }
</script>

<template>
    <div>
        <page-header>
            <!-- <div slot="right-side">
                <router-link :to="{name:'author-edit'}" class="py-1 px-2 btn-primary text-sm">
                    Approve/Reject Request
                </router-link>
            </div> -->
        </page-header>

        <div class="container">
            <!-- <div class="mb-10 flex items-center">
                <h1 class="inline font-semibold text-3xl mr-auto">Team</h1>

                <filters @showing="focusSearchInput" :is-filtered="isFiltered">
                    <input type="text" class="input mt-0 w-full"
                           placeholder="Search..."
                           v-model="searchQuery"
                           ref="searchInput">
                </filters>
            </div> -->

            <!-- <preloader v-if="!ready"></preloader> -->

            <!-- <div v-if="ready && entries.length == 0 && !isFiltered">
                <p>No authors were found, start by
                    <router-link :to="{name:'team-new'}" class="no-underline text-primary hover:text-primary-dark">adding an author</router-link>
                    .
                </p>
            </div> -->

            <div v-if=" entries.length == 0">
                No requests yet.
            </div>

            <div v-if="entries.length > 0">
                <div v-for="entry in entries" :key="entry.id" class="border-t border-very-light flex items-center py-5">
                    <div :title="entry.name">
                        <h2 class="text-xl font-semibold mb-3 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="h-6 w-6">
                                <path d="M7.72 2.15A3.13 3.13 0 006.5 4.74c.06.77.22 1.79.22 1.79s-.31.17-.31.85c.1 1.72.68.98.8 1.73.28 1.82.93 1.5.93 2.48 0 1.65-.68 2.42-2.8 3.34C3.2 15.85 1 17 1 19v1h18v-1c0-2-2.2-3.15-4.33-4.07-2.12-.92-2.8-1.69-2.8-3.34 0-.99.65-.66.93-2.48.12-.75.7 0 .8-1.73 0-.68-.3-.85-.3-.85s.15-1.01.21-1.8c.07-.81-.4-2.55-2.3-3.09-.33-.34-.55-.88.47-1.42-2.24-.1-2.76 1.06-3.96 1.93z"/>
                            </svg>
                               <span class="p-2"> {{truncate(entry.name, 68)}}</span>
                        </h2>
                        <h3 class="text-lg font-semibold mb-3 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="h-6 w-6 text-gray-500 hover:text-cyan-500 fill-current">
                                <path d="M1.57 5.29l7.5 4.02c.26.14.58.2.91.2.33 0 .65-.06.9-.2l7.5-4.02c.5-.27.96-1.29.06-1.29H1.52c-.9 0-.43 1.02.05 1.29zm17.04 2.2l-7.72 4.03c-.34.18-.58.2-.91.2s-.57-.03-.9-.2l-7.7-4.03C1 7.29 1 7.52 1 7.7V15c0 .42.57 1 1 1h16c.43 0 1-.58 1-1V7.7c0-.2 0-.42-.39-.21z"/>
                            </svg>
                            <span class="p-2">{{entry.email}}</span>
                        </h3>
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="h-6 w-6">
                                <path d="M19.4 7.42l-7.45-2-1.3-4.86a.79.79 0 00-.97-.53L.6 2.46a.78.78 0 00-.57.94l3.23 12.06c.1.4.54.64.97.52l3.6-.96-.48 1.83c-.11.41.15.83.57.95l8.11 2.17c.43.12.87-.12.98-.53l2.97-11.08a.78.78 0 00-.58-.94zM1.63 3.63l7.83-2.1 2.9 10.82-7.83 2.1-2.9-10.82zm14.05 14.83L8.86 16.6l.54-2 3.9-1.05a.78.78 0 00.58-.94l-1.5-5.63 5.94 1.63-2.64 9.85z"/>
                            </svg>
                            <p class="text-base font-semibold mb-3 p-2">
                                {{entry.bio}}
                            </p>
                        </div>
                        <small class="text-light">
                            Created {{timeAgo(entry.created_at)}}
                        </small>
                            <a href="#" @click.prevent="deleteAuthor(entry.id)" class="flex items-center no-underline text-red w-full block py-2 px-4">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="fill-current text-red-400 h-6 w-6">
                                <path d="M14.35 14.85a1.2 1.2 0 01-1.7 0L10 11.82l-2.65 3.03a1.2 1.2 0 11-1.7-1.7L8.41 10 5.65 6.85a1.2 1.2 0 111.7-1.7L10 8.18l2.65-3.03a1.2 1.2 0 111.7 1.7L11.59 10l2.76 3.15a1.2 1.2 0 010 1.7z"/>
                                </svg>
                                <span class="p-2">Delete</span>
                            </a>
                    </div>

                    <!-- <div class="ml-auto text-light mr-8">
                        {{entry.posts_count}} Post(s)
                    </div> -->

                    <!-- <router-link :to="{name:'author-edit', params:{id: entry.id}}" class="no-underline hidden lg:block">
                        <div class="w-16 h-16 rounded-full bg-cover" :style="{ backgroundImage: 'url(' + entry.avatar + ')' }"></div>
                    </router-link> -->
                </div>

                <!-- <div v-if="hasMoreEntries">
                    <div colspan="100" class="py-8 uppercase">
                        <a href="#" v-on:click.prevent="loadOlderEntries" v-if="!loadingMoreEntries" class="no-underline text-primary">Load more authors</a>

                        <span v-if="loadingMoreEntries">Loading...</span>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</template>
