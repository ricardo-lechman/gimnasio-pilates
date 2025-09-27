<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import { ref } from "vue";

defineProps({
  title: {
    type: String,
    default: "Admin",
  },
});

const mobileMenuOpen = ref(false);

const logout = () => {
  router.post(route("logout"));
};
</script>

<template>
  <div class="min-h-screen flex flex-col bg-gray-100">
    <Head :title="title" />

    <!-- Navbar (header) -->
<header class="bg-white shadow-md relative z-10">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center h-16">
      <!-- Logo / título -->
      <div class="flex-shrink-0">
        <Link href="/admin/configuracion" class="text-lg font-bold text-gray-800">
          Panel de Administración
        </Link>
      </div>

      <!-- Menú principal (desktop) -->
      <nav class="hidden md:flex space-x-6">
        <Link href="/admin/configuracion" class="text-gray-700 hover:text-indigo-600">Dashboard</Link>
        <Link href="/admin/users" class="text-gray-700 hover:text-indigo-600">Usuarios</Link>
        <Link href="/admin/pagos" class="text-gray-700 hover:text-indigo-600">Pagos</Link>
        <Link href="/admin/reservas" class="text-gray-700 hover:text-indigo-600">Reservas</Link>
        <Link href="/admin/cama" class="text-gray-700 hover:text-indigo-600">Cama</Link>
      </nav>

      <!-- Logout (desktop) -->
      <div class="hidden md:block">
        <button
          @click="logout"
          class="bg-red-100 text-red-700 px-4 py-2 rounded-md font-semibold hover:bg-red-200 transition"
        >
          🚪 Cerrar sesión
        </button>
      </div>

      <!-- Botón menú hamburguesa (mobile) -->
      <div class="md:hidden">
        <button
          @click="mobileMenuOpen = !mobileMenuOpen"
          class="p-2 rounded-md text-gray-700 hover:bg-gray-200"
        >
          <span v-if="!mobileMenuOpen">☰</span>
          <span v-else>✕</span>
        </button>
      </div>
    </div>
  </div>

  <!-- Menú desplegable mobile (desde header) -->
  <div
    v-show="mobileMenuOpen"
    class="md:hidden bg-white shadow-md border-t border-gray-200 absolute w-full top-full left-0"
  >
    <nav class="flex justify-center space-x-4 py-3">
      <Link href="/admin/configuracion" class="text-gray-700 hover:text-indigo-600" @click="mobileMenuOpen = false">Dashboard</Link>
      <Link href="/admin/users" class="text-gray-700 hover:text-indigo-600" @click="mobileMenuOpen = false">Usuarios</Link>
      <Link href="/admin/pagos" class="text-gray-700 hover:text-indigo-600" @click="mobileMenuOpen = false">Pagos</Link>
      <Link href="/admin/reservas" class="text-gray-700 hover:text-indigo-600" @click="mobileMenuOpen = false">Reservas</Link>
      <Link href="/admin/cama" class="text-gray-700 hover:text-indigo-600" @click="mobileMenuOpen = false">Cama</Link>

      <!-- Logout -->
      <button
        @click="logout"
        class="bg-red-100 text-red-700 px-3 py-1 rounded-md font-semibold hover:bg-red-200"
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

