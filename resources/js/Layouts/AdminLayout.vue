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

    <!-- Navbar -->
    <header class="bg-white shadow-md relative z-10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <!-- Logo / título -->
          <div class="flex-shrink-0">
            <Link href="/admin/configuracion" class="text-lg font-bold text-gray-800">
              Panel de Administración
            </Link>
          </div>

          <!-- Botón menú hamburguesa -->
          <div class="md:hidden">
            <button
              @click="mobileMenuOpen = !mobileMenuOpen"
              class="p-2 rounded-md text-gray-700 hover:bg-gray-200"
            >
              <span v-if="!mobileMenuOpen">☰</span>
              <span v-else>✕</span>
            </button>
          </div>

          <!-- Menú escritorio -->
          <nav class="hidden md:flex space-x-4">
            <Link href="/admin/configuracion" class="px-4 py-2 rounded-md text-gray-700 hover:text-indigo-600">
              Dashboard
            </Link>
            <Link href="/admin/users" class="px-4 py-2 rounded-md text-gray-700 hover:text-indigo-600">
              Usuarios
            </Link>
            <Link href="/admin/pagos" class="px-4 py-2 rounded-md text-gray-700 hover:text-indigo-600">
              Pagos
            </Link>
            <Link href="/admin/reservas" class="px-4 py-2 rounded-md text-gray-700 hover:text-indigo-600">
              Reservas
            </Link>
            <Link href="/admin/cama" class="px-4 py-2 rounded-md text-gray-700 hover:text-indigo-600">
              Cama
            </Link>
            <button
              @click="logout"
              class="px-4 py-2 rounded-md text-red-700 font-semibold hover:text-red-900 transition"
            >
              🚪 Cerrar sesión
            </button>
          </nav>
        </div>
      </div>

      <!-- Menú móvil -->
      <div
        v-show="mobileMenuOpen"
        class="md:hidden bg-white shadow-md border-t border-gray-200 absolute w-full top-full left-0"
      >
        <nav class="flex flex-col items-center space-y-3 py-4">
          <Link
            href="/admin/configuracion"
            class="w-full text-center px-4 py-2 rounded-md text-gray-700 hover:text-indigo-600"
            @click="mobileMenuOpen = false"
          >
            Dashboard
          </Link>
          <Link
            href="/admin/users"
            class="w-full text-center px-4 py-2 rounded-md text-gray-700 hover:text-indigo-600"
            @click="mobileMenuOpen = false"
          >
            Usuarios
          </Link>
          <Link
            href="/admin/pagos"
            class="w-full text-center px-4 py-2 rounded-md text-gray-700 hover:text-indigo-600"
            @click="mobileMenuOpen = false"
          >
            Pagos
          </Link>
          <Link
            href="/admin/reservas"
            class="w-full text-center px-4 py-2 rounded-md text-gray-700 hover:text-indigo-600"
            @click="mobileMenuOpen = false"
          >
            Reservas
          </Link>
          <Link
            href="/admin/cama"
            class="w-full text-center px-4 py-2 rounded-md text-gray-700 hover:text-indigo-600"
            @click="mobileMenuOpen = false"
          >
            Cama
          </Link>

          <hr class="border-gray-300 w-4/5 my-2" />

          <button
            @click="logout"
            class="w-full text-center px-4 py-2 rounded-md text-red-700 font-semibold hover:text-red-900 transition"
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
