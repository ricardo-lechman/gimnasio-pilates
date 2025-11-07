<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
  title: {
    type: String,
    default: 'User',
  },
});

const mobileMenuOpen = ref(false);

const logout = () => {
  router.post('/logout');
};
</script>

<template>
  <div class="min-h-screen flex flex-col bg-gray-100">
    <Head :title="title" />

    <!-- Navbar -->
    <header class="bg-gray-800 text-white shadow-md relative z-10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <!-- Logo / título -->
          <div class="flex flex-col md:flex-row md:items-center">
            <h1 class="text-xl font-bold">🏋️‍♀️ GIMNASIO PILATES</h1>

            <!-- Menú escritorio -->
            <nav class="hidden md:flex space-x-4 mt-2 md:mt-0 md:ml-6">
              <Link href="/users/users" class="px-4 py-2 bg-gray-700 rounded-md hover:bg-gray-600">Datos Personales</Link>
              <Link href="/users/reservas" class="px-4 py-2 bg-gray-700 rounded-md hover:bg-gray-600">Reservar</Link>
              <Link href="/users/pagos" class="px-4 py-2 bg-gray-700 rounded-md hover:bg-gray-600">Pagar</Link>
            </nav>
          </div>

          <!-- Botón menú hamburguesa móvil -->
          <div class="md:hidden">
            <button
              @click="mobileMenuOpen = !mobileMenuOpen"
              class="p-2 rounded-md hover:bg-gray-700"
            >
              <span v-if="!mobileMenuOpen">☰</span>
              <span v-else>✕</span>
            </button>
          </div>

          <!-- Logout escritorio -->
          <div class="hidden md:block">
            <button
              @click="logout"
              class="bg-red-200 text-red-700 px-4 py-2 rounded-md font-semibold hover:bg-red-300 transition"
            >
              🚪 Cerrar sesión
            </button>
          </div>
        </div>
      </div>

      <!-- Menú móvil -->
      <div
        v-show="mobileMenuOpen"
        class="md:hidden bg-gray-800 border-t border-gray-700 absolute w-full top-full left-0"
      >
        <nav class="flex flex-col items-center py-4 space-y-3 text-white">
          <Link href="/users/users" class="w-full text-center px-4 py-2 bg-gray-700 rounded-md hover:bg-gray-600" @click="mobileMenuOpen = false">
            Datos Personales
          </Link>
          <Link href="/users/reservas" class="w-full text-center px-4 py-2 bg-gray-700 rounded-md hover:bg-gray-600" @click="mobileMenuOpen = false">
            Reservar
          </Link>
          <Link href="/users/pagos" class="w-full text-center px-4 py-2 bg-gray-700 rounded-md hover:bg-gray-600" @click="mobileMenuOpen = false">
            Pagar
          </Link>
          <hr class="border-gray-600 w-4/5 my-2" />
          <button
            @click="logout"
            class="w-full text-center bg-red-200 text-red-700 px-4 py-2 rounded-md font-semibold hover:bg-red-300 transition"
          >
            🚪 Cerrar sesión
          </button>
        </nav>
      </div>
    </header>

    <!-- Contenido principal -->
    <main class="flex-1 p-6">
      <slot />
    </main>
  </div>
</template>
