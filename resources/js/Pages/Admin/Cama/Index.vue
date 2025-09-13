<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";

defineProps({
  camas: Array,
});

const form = ref({
  nombre: "",
  estado: "disponible",
});

function submit() {
  if (!form.value.nombre.trim()) {
    alert("El nombre de la cama es obligatorio.");
    return;
  }

  router.post(route("admin.camas.store"), form.value, {
    onSuccess: () => {
      form.value.nombre = "";
      form.value.estado = "disponible";
    },
  });
}

function update(cama) {
  router.put(route("admin.camas.update", cama.id), {
    nombre: cama.nombre,
    estado: cama.estado,
  });
}

function destroy(id) {
  if (confirm("¿Eliminar esta cama?")) {
    router.delete(route("admin.camas.destroy", id));
  }
}
</script>

<template>
  <AdminLayout title="Gestión de Camas">
    <h1 class="text-2xl font-bold mb-4">Gestión de Camas</h1>

    <!-- Formulario agregar cama -->
    <form @submit.prevent="submit" class="mb-6 flex gap-2">
      <input
        v-model="form.nombre"
        placeholder="Nombre de la cama"
        class="border p-2 flex-1 rounded"
      />
      <select v-model="form.estado" class="border p-2 rounded">
        <option value="disponible">Disponible</option>
        <option value="ocupada">Ocupada</option>
        <option value="mantenimiento">Mantenimiento</option>
      </select>
      <button class="bg-blue-500 text-white px-4 py-2 rounded">Agregar</button>
    </form>

    <!-- Listado de camas -->
    <table class="w-full border rounded overflow-hidden">
      <thead>
        <tr class="bg-gray-200 text-left">
          <th class="p-2">ID</th>
          <th class="p-2">Nombre</th>
          <th class="p-2">Estado</th>
          <th class="p-2">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="cama in camas" :key="cama.id" class="border-t">
          <td class="p-2">{{ cama.id }}</td>
          <td class="p-2">
            <input v-model="cama.nombre" class="border p-1 w-full rounded" />
          </td>
          <td class="p-2">
            <select v-model="cama.estado" class="border p-1 rounded">
              <option value="disponible">Disponible</option>
              <option value="ocupada">Ocupada</option>
              <option value="mantenimiento">Mantenimiento</option>
            </select>
          </td>
          <td class="p-2 flex gap-2">
            <button
              @click="update(cama)"
              class="bg-green-500 text-white px-2 py-1 rounded"
            >
              Guardar
            </button>
            <button
              @click="destroy(cama.id)"
              class="bg-red-500 text-white px-2 py-1 rounded"
            >
              Eliminar
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </AdminLayout>
</template>
