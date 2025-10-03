<script setup>
import UsersLayout from "@/Layouts/UsersLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref, reactive } from "vue";

const props = defineProps({
  reservas: Array,
  camas: Array,
});

const selectedReserva = ref(null);

const showAddModal = ref(false);
const showEditModal = ref(false);

const form = reactive({
  cama_id: "",
  fecha: "",
});

const openAddModal = () => {
  Object.assign(form, {
    cama_id: "",
    fecha: "",
  });
  showAddModal.value = true;
};

const openEditModal = () => {
  if (!selectedReserva.value)
    return alert("Selecciona una reserva para modificar.");

  const reserva = props.reservas.find((r) => r.id === selectedReserva.value);
  Object.assign(form, {
    cama_id: reserva.cama_id,
    fecha: reserva.fecha,
  });
  showEditModal.value = true;
};

const submitAdd = () => {
  router.post(route("users.reservas.store"), { ...form }, {
    onSuccess: () => {
      alert("Reserva creada correctamente.");
      showAddModal.value = false;
      router.reload();
    },
    onError: (errors) => {
      alert("Error al crear la reserva.");
      console.error(errors);
    },
  });
};

const submitEdit = () => {
  router.put(route("users.reservas.update", selectedReserva.value), { ...form }, {
    onSuccess: () => {
      alert("Reserva actualizada correctamente.");
      showEditModal.value = false;
      router.reload();
    },
    onError: (errors) => {
      alert("Error al actualizar reserva.");
      console.error(errors);
    },
  });
};

const deleteReserva = () => {
  if (!selectedReserva.value)
    return alert("Selecciona una reserva para eliminar.");
  if (confirm("¿Eliminar esta reserva?")) {
    router.delete(route("users.reservas.destroy", selectedReserva.value), {
      onSuccess: () => {
        alert("Reserva eliminada correctamente.");
        router.reload();
      },
      onError: (errors) => {
        alert("Error al eliminar reserva.");
        console.error(errors);
      },
    });
  }
};
</script>

<template>
  <Head title="Mis Reservas" />
  <UsersLayout title="Reservas">
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Mis Reservas
      </h2>
    </template>

    <div class="py-6 px-4">
      <div class="flex gap-2 mb-4">
        <button
          class="bg-black text-black px-3 py-1 rounded hover:bg-gray-800"
          @click="openAddModal"
        >
          Nueva Reserva
        </button>
        <button
          class="bg-black text-black px-3 py-1 rounded hover:bg-gray-800"
          @click="openEditModal"
        >
          Modificar
        </button>
        <button
          class="bg-black text-black px-3 py-1 rounded hover:bg-gray-800"
          @click="deleteReserva"
        >
          Eliminar
        </button>
      </div>

      <div v-if="props.reservas.length === 0" class="text-gray-500">
        No tienes reservas registradas.
      </div>

      <div v-else class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-2">Seleccionar</th>
              <th class="px-4 py-2">ID</th>
              <th class="px-4 py-2">Cama</th>
              <th class="px-4 py-2">Fecha</th>
              <th class="px-4 py-2">Estado</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr
              v-for="reserva in props.reservas"
              :key="reserva.id"
              :class="selectedReserva === reserva.id ? 'bg-yellow-100' : ''"
              @click="selectedReserva = reserva.id"
            >
              <td class="px-4 py-2 text-center">
                <input type="radio" :value="reserva.id" v-model="selectedReserva" />
              </td>
              <td class="px-4 py-2">{{ reserva.id }}</td>
              <td class="px-4 py-2">{{ reserva.cama?.nombre }}</td>
              <td class="px-4 py-2">{{ reserva.fecha }}</td>
              <td class="px-4 py-2">{{ reserva.estado }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal Agregar -->
    <div
      v-if="showAddModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
      <div class="bg-black p-6 rounded w-96">
        <h3 class="text-lg font-semibold mb-4">Nueva Reserva</h3>
        <form @submit.prevent="submitAdd" class="space-y-2">
          <select v-model="form.cama_id" class="w-full border p-1" required>
            <option disabled value="">Seleccione una cama</option>
            <option v-for="c in props.camas" :key="c.id" :value="c.id">
              {{ c.nombre }}
            </option>
          </select>
          <input
            v-model="form.fecha"
            type="datetime-local"
            class="w-full border p-1"
            required
          />

          <div class="flex justify-end gap-2 mt-2">
            <button
              type="button"
              class="bg-gray-500 text-black px-3 py-1 rounded hover:bg-gray-600"
              @click="showAddModal = false"
            >
              Cancelar
            </button>
            <button
              type="submit"
              class="bg-green-600 text-black px-3 py-1 rounded hover:bg-green-700"
            >
              Guardar
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal Editar -->
    <div
      v-if="showEditModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
      <div class="bg-black p-6 rounded w-96">
        <h3 class="text-lg font-semibold mb-4">Editar Reserva</h3>
        <form @submit.prevent="submitEdit" class="space-y-2">
          <select v-model="form.cama_id" class="w-full border p-1" required>
            <option disabled value="">Seleccione una cama</option>
            <option v-for="c in props.camas" :key="c.id" :value="c.id">
              {{ c.nombre }}
            </option>
          </select>
          <input
            v-model="form.fecha"
            type="datetime-local"
            class="w-full border p-1"
            required
          />

          <div class="flex justify-end gap-2 mt-2">
            <button
              type="button"
              class="bg-gray-500 text-black px-3 py-1 rounded hover:bg-gray-600"
              @click="showEditModal = false"
            >
              Cancelar
            </button>
            <button
              type="submit"
              class="bg-green-600 text-black px-3 py-1 rounded hover:bg-green-700"
            >
              Guardar
            </button>
          </div>
        </form>
      </div>
    </div>
  </UsersLayout>
</template>

