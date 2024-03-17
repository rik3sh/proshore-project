<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    errors: Object
});


const form = useForm({
    title: '',
    expiry_date: ''
})

</script>

<template>
    <Head title="Questionnaires" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Questionnaire</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="form.post(route('questionnaires.store'))">
                            <div>
                                <div>
                                    <label for="title" class="block font-medium text-sm text-gray-700">Title</label>
                                    <input type="text" v-model="form.title" id="title" class="block mt-1 w-full">
                                    <div v-if="errors.title" class="text-red-600">
                                        {{ errors.title }}
                                    </div>
                                </div>
                            </div>

                            <div class="py-4">
                                <div>
                                    <label for="expiry_date" class="block font-medium text-sm text-gray-700">Expiry Date</label>
                                    <input type="date" v-model="form.expiry_date" id="expiry_date" class="block mt-1 w-full">
                                    <div v-if="errors.expiry_date" class="text-red-600">
                                        {{ errors.expiry_date }}
                                    </div>
                                </div>
                            </div>

                            <div class="py-2">
                                <button type="submit" class="inline-block px-2 py-2 bg-blue-500 text-white rounded">Save</button>
                                <Link :href="route('questionnaires.index')" class="inline-block mx-1 px-2 py-2 bg-yellow-500 text-white rounded mb-2">Cancel</Link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
