<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <div>
        <Head :title="title" />

        <header class="bg-gray-800 text-white p-4">
            <h1 class="text-xl">Panel de Administración</h1>
            <nav class="mt-2">
                <Link href="/admin" class="mr-4">Dashboard</Link>
                <Link href="/admin/usuarios" class="mr-4">Usuarios</Link>
                <Link href="/admin/turnos" class="mr-4">Turnos</Link>
            </nav>
        </header>

        <main class="p-6">
            <slot />
        </main>
    </div>
</template>

<script>
export default {
    props: {
        title: {
            type: String,
            default: 'Admin',
        },
    },
};
</script>

