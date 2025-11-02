<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref, reactive, computed, watch } from "vue";
import { format, parseISO } from "date-fns";

const props = defineProps({
  reservas: Array,
  usuarios: Array,
  camas: Array,
  cronogramas: Array,
});

const selectedReserva = ref(null);

const showAddModal = ref(false);
const showEditModal = ref(false);

const form = reactive({
  user_id: "",
  cama_id: "",
  fecha: "",
  cronograma_id: "",
  status: "pendiente",
});

// Estados válidos
const estados = ["pendiente", "confirmado", "cancelado"];

// Abre modal de agregar
const openAddModal = () => {
  Object.assign(form, {
    user_id: "",
    cama_id: "",
    fecha: "",
    cronograma_id: "",
    status: "pendiente",
  });
  showAddModal.value = true;
};

// Abre modal de editar
const openEditModal = () => {
  if (!selectedReserva.value) return alert("Selecciona una reserva para modificar.");

  const reserva = props.reservas.find((r) => r.id === selectedReserva.value);
  Object.assign(form, {
    user_id: reserva.user_id,
    cama_id: reserva.cama_id,
    fecha: reserva.cronograma.date,
    cronograma_id: reserva.cronograma_id,
    status: reserva.status,
  });
  showEditModal.value = true;
};

// Computed: cronogramas disponibles según fecha y cama
const availableCronogramas = computed(() => {
  if (!form.fecha || !form.cama_id) return [];
  return props.cronogramas
    .filter(c => format(new Date(c.date), "yyyy-MM-dd") === form.fecha)
    .filter(c => !props.reservas.some(r => r.cama_id === form.cama_id && r.cronograma_id === c.id));
});

// Formateo de cronograma
const formatFecha = (cronograma) => {
  const fecha = parseISO(cronograma.date);
  return `${format(fecha, "dd/MM/yyyy")} ${cronograma.start_time} - ${cronograma.end_time}`;
};

// Submit agregar
const submitAdd = () => {
  router.post(route("admin.reservas.store"), { ...form }, {
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

// Submit editar
const submitEdit = () => {
  router.put(route("admin.reservas.update", selectedReserva.value), { ...form }, {
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

// Eliminar reserva
const deleteReserva = () => {
  if (!selectedReserva.value) return alert("Selecciona una reserva para eliminar.");
  if (confirm("¿Eliminar esta reserva?")) {
    router.delete(route("admin.reservas.destroy", selectedReserva.value), {
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

// Reset cronograma al cambiar fecha o cama
watch([() => form.fecha, () => form.cama_id], () => {
  form.cronograma_id = "";
});
</script>

<template>
  <Head title="Reservas" />
  <AdminLayout title="Reservas">
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Lista de Reservas
      </h2>
    </template>

    <div class="py-6 px-4">
      <div class="flex gap-2 mb-4">
        <button class="bg-black text-black px-3 py-1 rounded hover:bg-gray-700" @click="openAddModal">
          Agregar
        </button>
        <button class="bg-black text-black px-3 py-1 rounded hover:bg-gray-700" @click="openEditModal">
          Modificar
        </button>
        <button class="bg-black text-black px-3 py-1 rounded hover:bg-gray-700" @click="deleteReserva">
          Eliminar
        </button>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-2">Seleccionar</th>
              <th class="px-4 py-2">ID</th>
              <th class="px-4 py-2">Usuario</th>
              <th class="px-4 py-2">Cama</th>
              <th class="px-4 py-2">Turno</th>
              <th class="px-4 py-2">Estado</th>
            </tr>
          </thead>
          <tbody class="bg-black divide-y divide-gray-200">
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
              <td class="px-4 py-2">{{ reserva.user?.name }}</td>
              <td class="px-4 py-2">{{ reserva.cama?.nombre }}</td>
              <td class="px-4 py-2">{{ formatFecha(reserva.cronograma) }}</td>
              <td class="px-4 py-2">{{ reserva.status }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal Agregar -->
    <div v-if="showAddModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded w-96">
        <h3 class="text-lg font-semibold mb-4">Agregar Reserva</h3>
        <form @submit.prevent="submitAdd" class="space-y-2">
          <select v-model="form.user_id" class="w-full border p-1" required>
            <option disabled value="">Seleccione un usuario</option>
            <option v-for="u in props.usuarios" :key="u.id" :value="u.id">
              {{ u.name }} ({{ u.email }})
            </option>
          </select>

          <select v-model="form.cama_id" class="w-full border p-1" required>
            <option disabled value="">Seleccione una cama</option>
            <option v-for="c in props.camas" :key="c.id" :value="c.id">
              {{ c.nombre }}
            </option>
          </select>

          <input type="date" v-model="form.fecha" class="w-full border p-1" required />

          <select v-model="form.cronograma_id" class="w-full border p-1" required>
            <option disabled value="">Seleccione un horario de la lista</option>
            <option v-for="c in availableCronogramas" :key="c.id" :value="c.id">
              {{ c.start_time }} - {{ c.end_time }}
            </option>
          </select>

          <select v-model="form.status" class="w-full border p-1" required>
            <option v-for="e in estados" :key="e" :value="e">{{ e }}</option>
          </select>

          <div class="flex justify-end gap-2 mt-2">
            <button type="button" class="bg-black text-black px-3 py-1 rounded hover:bg-gray-800" @click="showAddModal = false">
              Cancelar
            </button>
            <button type="submit" class="bg-black text-black px-3 py-1 rounded hover:bg-gray-800">
              Agregar
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal Editar -->
    <div v-if="showEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded w-96">
        <h3 class="text-lg font-semibold mb-4">Editar Reserva</h3>
        <form @submit.prevent="submitEdit" class="space-y-2">
          <select v-model="form.user_id" class="w-full border p-1" required>
            <option disabled value="">Seleccione un usuario</option>
            <option v-for="u in props.usuarios" :key="u.id" :value="u.id">
              {{ u.name }} ({{ u.email }})
            </option>
          </select>

          <select v-model="form.cama_id" class="w-full border p-1" required>
            <option disabled value="">Seleccione una cama</option>
            <option v-for="c in props.camas" :key="c.id" :value="c.id">
              {{ c.nombre }}
            </option>
          </select>

          <input type="date" v-model="form.fecha" class="w-full border p-1" required />

          <select v-model="form.cronograma_id" class="w-full border p-1" required>
            <option disabled value="">Seleccione un horario de la lista</option>
            <option v-for="c in availableCronogramas" :key="c.id" :value="c.id">
              {{ c.start_time }} - {{ c.end_time }}
            </option>
          </select>

          <select v-model="form.status" class="w-full border p-1" required>
            <option v-for="e in estados" :key="e" :value="e">{{ e }}</option>
          </select>

          <div class="flex justify-end gap-2 mt-2">
            <button type="button" class="bg-black text-black px-3 py-1 rounded hover:bg-gray-800" @click="showEditModal = false">
              Cancelar
            </button>
            <button type="submit" class="bg-black text-black px-3 py-1 rounded hover:bg-gray-800">
              Guardar
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>






