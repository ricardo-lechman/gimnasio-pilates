<script setup>
import { Head, Link, router } from '@inertiajs/vue3';

// Función logout con Inertia
const logout = () => {
  router.post('/logout');
};
</script>

<template>
  <div>
    <Head :title="title" />

    <header class="bg-gray-800 text-white p-4 flex justify-between items-center">
      <div>
        <h1 class="text-xl font-bold">🏋️‍♀️ GIMNASIO PILATES</h1>
        <nav class="mt-2 space-x-4">
          <Link href="/users/users" class="hover:underline">Datos Personales</Link>
          <Link href="/users/cronograma" class="hover:underline">Ver Cronograma</Link>
          <Link href="/users/reservas" class="hover:underline">Reservar</Link>
          <Link href="/users/pagos" class="hover:underline">Pagar</Link>
        </nav>
      </div>

      <!-- Logout (desktop) -->
      <div>
        <button
          @click="logout"
          class="bg-red-200 text-red-700 px-4 py-2 rounded-md font-semibold hover:bg-red-200 transition"
        >
          🚪 Cerrar sesión
        </button>
      </div>
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
      default: 'User',
    },
  },
};
</script>

