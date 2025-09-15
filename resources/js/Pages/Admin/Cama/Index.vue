<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";

// Define las props que el componente recibe del controlador
defineProps({
  camas: Array,
  errors: Object, // Necesario para mostrar errores de validación del backend
});

// Estado reactivo para el formulario de agregar nueva cama
const form = ref({
  nombre: "",
  estado: "disponible",
});

/**
 * Envía el formulario para crear una nueva cama.
 */
function submit() {
  router.post(route("admin.camas.store"), form.value, {
    onSuccess: () => {
      // Limpia el formulario después de una creación exitosa
      form.value.nombre = "";
      form.value.estado = "disponible";
    },
    onError: (errors) => {
      console.error("Error al crear la cama:", errors);
      alert("Hubo un error al crear la cama. Revisa los mensajes de error.");
    },
  });
}

/**
 * Actualiza una cama existente. Se llama desde el botón "Guardar" de cada fila.
 * @param {object} cama - El objeto de la cama a actualizar.
 */
function update(cama) {
  router.put(
    route("admin.camas.update", cama.id),
    {
      nombre: cama.nombre,
      estado: cama.estado,
    },
    {
      preserveScroll: true, // Evita que la página se desplace al inicio
      onSuccess: () => {
        // Opcional: podrías mostrar una notificación sutil aquí
      },
      onError: (errors) => {
        console.error("Error al actualizar la cama:", errors);
        alert(`Error al actualizar la cama "${cama.nombre}".`);
      },
    }
  );
}

/**
 * Elimina una cama, con una confirmación previa.
 * @param {object} cama - El objeto de la cama a eliminar.
 */
function destroy(cama) {
  if (confirm(`¿Estás seguro de que quieres eliminar la cama "${cama.nombre}"?`)) {
    router.delete(route("admin.camas.destroy", cama.id), {
      preserveScroll: true,
      onError: (errors) => {
        console.error("Error al eliminar la cama:", errors);
        alert("Hubo un error al eliminar la cama.");
      },
    });
  }
}
</script>

<template>
  <AdminLayout title="Gestión de Camas">
    <div class="p-4 md:p-6">
      <div
        v-if="$page.props.flash?.success"
        class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg"
        role="alert"
      >
        {{ $page.props.flash.success }}
      </div>

      <h1 class="text-2xl font-bold text-gray-800 mb-6">Gestión de Camas</h1>

      <form
        @submit.prevent="submit"
        class="mb-8 p-4 bg-white rounded-lg shadow-md flex flex-wrap items-end gap-4"
      >
        <div class="flex-grow">
          <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre de la cama</label>
          <input
            id="nombre"
            v-model="form.nombre"
            placeholder="Ej: Habitación 101 - Cama A"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
          />
          <div v-if="errors.nombre" class="text-sm text-red-600 mt-1">{{ errors.nombre }}</div>
        </div>
        <div class="w-full sm:w-auto">
          <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
          <select
            id="estado"
            v-model="form.estado"
            class="mt-1 block w-full pl-3 pr-10 py-2 border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md"
          >
            <option value="disponible">Disponible</option>
            <option value="ocupada">Ocupada</option>
            <option value="mantenimiento">Mantenimiento</option>
          </select>
        </div>
        <button
          type="submit"
          class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition"
        >
          Agregar Cama
        </button>
      </form>

      <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="w-full border-collapse">
          <thead>
            <tr class="bg-gray-100 text-left text-sm font-semibold text-gray-600 uppercase">
              <th class="p-3">ID</th>
              <th class="p-3">Nombre</th>
              <th class="p-3">Estado</th>
              <th class="p-3 text-center">Acciones</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="cama in camas" :key="cama.id" class="hover:bg-gray-50">
              <td class="p-3 text-sm text-gray-500">{{ cama.id }}</td>
              <td class="p-3">
                <input
                  v-model="cama.nombre"
                  class="w-full p-1 border border-gray-300 rounded-md"
                />
              </td>
              <td class="p-3">
                <select
                  v-model="cama.estado"
                  class="w-full p-1 border border-gray-300 rounded-md"
                >
                  <option value="disponible">Disponible</option>
                  <option value="ocupada">Ocupada</option>
                  <option value="mantenimiento">Mantenimiento</option>
                </select>
              </td>
              <td class="p-3 flex justify-center items-center gap-2">
                <button
                  @click="update(cama)"
                  class="bg-green-500 text-white px-3 py-1 rounded-md text-xs font-semibold hover:bg-green-600 transition"
                >
                  Guardar
                </button>
                <button
                  @click="destroy(cama)"
                  class="bg-red-500 text-white px-3 py-1 rounded-md text-xs font-semibold hover:bg-red-600 transition"
                >
                  Eliminar
                </button>
              </td>
            </tr>
            <tr v-if="camas.length === 0">
                <td colspan="4" class="text-center text-gray-500 py-6">No hay camas para mostrar.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AdminLayout>
</template>



