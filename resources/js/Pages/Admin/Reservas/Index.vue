<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";

defineProps({
  reservas: Array, // viene del controlador
  usuarios: Array, // lista de usuarios para el select
  camas: Array,    // lista de camas para el select
});

// Formulario para nueva reserva
const form = ref({
  user_id: "",
  cama_id: "",
  fecha: "",
  estado: "activa",
});

// Crear reserva
function submit() {
  if (!form.value.user_id || !form.value.cama_id || !form.value.fecha) {
    alert("Todos los campos son obligatorios");
    return;
  }

  router.post(route("admin.reservas.store"), form.value, {
    onSuccess: () => {
      form.value.user_id = "";
      form.value.cama_id = "";
      form.value.fecha = "";
      form.value.estado = "activa";
    },
  });
}

// Editar reserva
function update(reserva) {
  router.put(route("admin.reservas.update", reserva.id), {
    user_id: reserva.user_id,
    cama_id: reserva.cama_id,
    fecha: reserva.fecha,
    estado: reserva.estado,
  });
}

// Eliminar reserva
function destroy(id) {
  if (confirm("¿Eliminar esta reserva?")) {
    router.delete(route("admin.reservas.destroy", id));
  }
}
</script>

<template>
  <AdminLayout title="Gestión de Reservas">
    <h1 class="text-2xl font-bold mb-4">Gestión de Reservas</h1>

    <!-- Formulario agregar reserva -->
    <form @submit.prevent="submit" class="mb-6 flex flex-wrap gap-2 items-center">
      <!-- Usuario -->
      <select v-model="form.user_id" class="border p-2 rounded">
        <option disabled value="">Seleccione un usuario</option>
        <option v-for="u in usuarios" :key="u.id" :value="u.id">
          {{ u.name }} ({{ u.email }})
        </option>
      </select>

      <!-- Cama -->
      <select v-model="form.cama_id" class="border p-2 rounded">
        <option disabled value="">Seleccione una cama</option>
        <option v-for="c in camas" :key="c.id" :value="c.id">
          {{ c.nombre }}
        </option>
      </select>

      <!-- Fecha -->
      <input type="datetime-local" v-model="form.fecha" class="border p-2 rounded" />

      <!-- Estado -->
      <select v-model="form.estado" class="border p-2 rounded">
        <option value="activa">Activa</option>
        <option value="cancelada">Cancelada</option>
      </select>

      <button class="bg-blue-500 text-white px-4 py-2 rounded">Agregar</button>
    </form>

    <!-- Listado de reservas -->
    <table class="w-full border rounded overflow-hidden">
      <thead>
        <tr class="bg-gray-200 text-left">
          <th class="p-2">ID</th>
          <th class="p-2">Usuario</th>
          <th class="p-2">Cama</th>
          <th class="p-2">Fecha</th>
          <th class="p-2">Estado</th>
          <th class="p-2">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="reserva in reservas" :key="reserva.id" class="border-t">
          <td class="p-2">{{ reserva.id }}</td>

          <!-- Usuario -->
          <td class="p-2">
            <select v-model="reserva.user_id" class="border p-1 rounded">
              <option v-for="u in usuarios" :key="u.id" :value="u.id">
                {{ u.name }}
              </option>
            </select>
          </td>

          <!-- Cama -->
          <td class="p-2">
            <select v-model="reserva.cama_id" class="border p-1 rounded">
              <option v-for="c in camas" :key="c.id" :value="c.id">
                {{ c.nombre }}
              </option>
            </select>
          </td>

          <!-- Fecha -->
          <td class="p-2">
            <input type="datetime-local" v-model="reserva.fecha" class="border p-1 rounded w-full" />
          </td>

          <!-- Estado -->
          <td class="p-2">
            <select v-model="reserva.estado" class="border p-1 rounded">
              <option value="activa">Activa</option>
              <option value="cancelada">Cancelada</option>
            </select>
          </td>

          <!-- Acciones -->
          <td class="p-2 flex gap-2">
            <button
              @click="update(reserva)"
              class="bg-green-500 text-white px-2 py-1 rounded"
            >
              Guardar
            </button>
            <button
              @click="destroy(reserva.id)"
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

