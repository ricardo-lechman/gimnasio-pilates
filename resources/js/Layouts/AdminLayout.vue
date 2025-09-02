<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <div>
        <Head :title="title"></Head>

        <header class="bg-gray-800 text-white p-4">
            <Link href="/admin/configuracion" class="mr-a"><h1 class="text-xl">Panel de Administración</h1></Link>
            <nav class="mt-2">
                <Link href="/admin/configuracion" class="mr-4">Dashboard</Link> 
                <Link href="/admin/users" class="mr-4">Usuarios</Link>  
                <Link href="/admin/cronograma" class="mr-4">Turnos</Link> 
                <Link href="/admin/pagos" class="mr-4">Pagos</Link> 
                <Link href="/admin/reservas" class="mr-4">Reservas</Link> 
                <Link href="/admin/cama" class="mr-4">Cama</Link>
                <Link href="/admin/estadisticas" class="mr-a">Estadisticas</Link> 
            </nav>
        </header>

        <main class="p-6">
            <slot></slot>
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

