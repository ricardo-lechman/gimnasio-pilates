<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    title: {
        type: String,
        default: 'Admin',
    },
});

const showingNavigationDropdown = ref(false);

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div>
        <Head :title="title"></Head>

        <header class="bg-gray-800 text-white p-4">
            <div class="flex justify-between items-center">
                <Link href="/admin/configuracion" class="text-xl font-bold">Panel de Administración</Link>

                <!-- 🔹 Botón de logout visible en desktop -->
                <button 
                    @click="logout" 
                    class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded shadow font-semibold">
                    🚪 Cerrar sesión
                </button>
            </div>

            <nav class="mt-2 flex flex-wrap space-x-4">
                <Link href="/admin/configuracion">Dashboard</Link>
                <Link href="/admin/users">Usuarios</Link>
                <Link href="/admin/pagos">Pagos</Link>
                <Link href="/admin/reservas">Reservas</Link>
                <Link href="/admin/cama">Cama</Link>
            </nav>

            <!-- Hamburger / menú responsive -->
            <div class="sm:hidden mt-2">
                <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="p-2 bg-gray-700 rounded">
                    <span v-if="!showingNavigationDropdown">☰</span>
                    <span v-else>✕</span>
                </button>

                <div v-show="showingNavigationDropdown" class="mt-2 flex flex-col space-y-2">
                    <Link href="/admin/configuracion">Dashboard</Link>
                    <Link href="/admin/users">Usuarios</Link>
                    <Link href="/admin/pagos">Pagos</Link>
                    <Link href="/admin/reservas">Reservas</Link>
                    <Link href="/admin/cama">Cama</Link>

                    <!-- 🔹 Logout en responsive -->
                    <button @click="logout" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded mt-2">
                        🚪 Cerrar sesión
                    </button>
                </div>
            </div>
        </header>

        <main class="p-6">
            <slot></slot>
        </main>
    </div>
</template>
