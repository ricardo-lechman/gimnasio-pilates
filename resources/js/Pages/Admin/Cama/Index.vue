<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref, reactive } from "vue";

const props = defineProps({
  camas: Array,
});

const selectedCama = ref(null);

const showAddModal = ref(false);
const showEditModal = ref(false);

const form = reactive({
  nombre: "",
  estado: "disponible",
});

// Abrir modal agregar
const openAddModal = () => {
  Object.assign(form, { nombre: "", estado: "disponible" });
  showAddModal.value = true;
};

// Abrir modal editar
const openEditModal = () => {
  if (!selectedCama.value) return alert("Selecciona una cama para modificar.");
  const cama = props.camas.find((c) => c.id === selectedCama.value);
  Object.assign(form, { nombre: cama.nombre, estado: cama.estado });
  showEditModal.value = true;
};

// Crear cama
const submitAdd = () => {
  router.post("/admin/camas", { ...form }, {
    onSuccess: () => {
      alert("Cama creada correctamente.");
      showAddModal.value = false;
      router.reload();
    },
    onError: (errors) => {
      console.error(errors);
      alert("Error al crear la cama.");
    },
  });
};

// Actualizar cama
const submitEdit = () => {
  router.put(`/admin/camas/${selectedCama.value}`, { ...form }, {
    onSuccess: () => {
      alert("Cama actualizada correctamente.");
      showEditModal.value = false;
      router.reload();
    },
    onError: (errors) => {
      console.error(errors);
      alert("Error al actualizar la cama.");
    },
  });
};

// Eliminar cama
const deleteCama = () => {
  if (!selectedCama.value) return alert("Selecciona una cama para eliminar.");
  if (!confirm("¿Seguro que quieres eliminar esta cama?")) return;

  router.delete(`/admin/camas/${selectedCama.value}`, {
    onSuccess: () => {
      alert("Cama eliminada correctamente.");
      router.reload();
    },
    onError: (errors) => {
      console.error(errors);
      alert("Error al eliminar la cama.");
    },
  });
};
</script>

<template>
  <Head title="Camas" />
  <AdminLayout title="Camas">
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Lista de Camas
      </h2>
    </template>

    <div class="py-6 px-4">
      <div class="flex gap-2 mb-4">
        <button
          class="bg-black text-black px-3 py-1 rounded hover:bg-gray-800"
          @click="openAddModal"
        >
          Agregar
        </button>
        <button
          class="bg-black text-black px-3 py-1 rounded hover:bg-gray-800"
          @click="openEditModal"
        >
          Modificar
        </button>
        <button
          class="bg-black text-black px-3 py-1 rounded hover:bg-gray-800"
          @click="deleteCama"
        >
          Eliminar
        </button>
      </div>

      <!-- Tabla -->
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-2">Seleccionar</th>
              <th class="px-4 py-2">ID</th>
              <th class="px-4 py-2">Nombre</th>
              <th class="px-4 py-2">Estado</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr
              v-for="cama in props.camas"
              :key="cama.id"
              :class="selectedCama === cama.id ? 'bg-yellow-100' : ''"
              @click="selectedCama = cama.id"
            >
              <td class="px-4 py-2 text-center">
                <input type="radio" :value="cama.id" v-model="selectedCama" />
              </td>
              <td class="px-4 py-2">{{ cama.id }}</td>
              <td class="px-4 py-2">{{ cama.nombre }}</td>
              <td class="px-4 py-2">{{ cama.estado }}</td>
            </tr>
            <tr v-if="props.camas.length === 0">
              <td colspan="4" class="text-center text-gray-500 py-6">
                No hay camas registradas.
              </td>
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
      <div class="bg-white p-6 rounded w-96">
        <h3 class="text-lg font-semibold mb-4">Agregar Cama</h3>
        <form @submit.prevent="submitAdd" class="space-y-2">
          <input
            v-model="form.nombre"
            placeholder="Nombre"
            class="w-full border p-1"
            required
          />
          <select v-model="form.estado" class="w-full border p-1">
            <option value="disponible">Disponible</option>
            <option value="ocupada">Ocupada</option>
            <option value="mantenimiento">Mantenimiento</option>
          </select>
          <div class="flex justify-end gap-2 mt-2">
            <button
              type="button"
              class="bg-gray-400 text-black px-3 py-1 rounded hover:bg-gray-500"
              @click="showAddModal = false"
            >
              Cancelar
            </button>
            <button
              type="submit"
              class="bg-blue-600 text-black px-3 py-1 rounded hover:bg-blue-700"
            >
              Agregar
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
      <div class="bg-white p-6 rounded w-96">
        <h3 class="text-lg font-semibold mb-4">Editar Cama</h3>
        <form @submit.prevent="submitEdit" class="space-y-2">
          <input
            v-model="form.nombre"
            placeholder="Nombre"
            class="w-full border p-1"
            required
          />
          <select v-model="form.estado" class="w-full border p-1">
            <option value="disponible">Disponible</option>
            <option value="ocupada">Ocupada</option>
            <option value="mantenimiento">Mantenimiento</option>
          </select>
          <div class="flex justify-end gap-2 mt-2">
            <button
              type="button"
              class="bg-gray-400 text-black px-3 py-1 rounded hover:bg-gray-500"
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
  </AdminLayout>
</template>





