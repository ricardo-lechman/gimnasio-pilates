<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <div>
        <Head :title="title"></Head>

        <header class="bg-gray-800 text-white p-4">
            <h1>GIMNASIO PILATES</h1>
            <nav class="mt-2">
                <Link href="/users/users" class="mr-4">Datos Personales</Link>
                <Link href="/users/cronograma" class="mr-4">Ver Cronograma</Link>
                <Link href="/users/reservas" class="mr-4">Reservar</Link>
                <Link href="/users/pagos" class="mr-4">Pagar</Link>
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
            default: 'user',
        },
    },
};
</script>

