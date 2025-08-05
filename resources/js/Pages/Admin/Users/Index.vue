<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
defineProps({ users: Array });
</script>

<template>
    <Head title="Usuarios" />
    <AdminLayout title="Usuarios">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Lista de Usuarios
            </h2>
        </template>

        <div class="py-6 px-4">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rol</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="user in users" :key="user.id">
                            <td class="px-4 py-2 text-sm text-gray-900">{{ user.id }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ user.name }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ user.email }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ user.role }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>


