<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref, reactive } from "vue";

const props = defineProps({
  pagos: Array,
  usuarios: Array,
  reservas: Array,
});

const selectedPago = ref(null);
const showAddModal = ref(false);
const showEditModal = ref(false);

const form = reactive({
  user_id: "",
  reserva_id: "",
  monto: "",
  metodo_pago: "",
  comprobante: null,
  fecha_pago: "",
  estado: "pendiente",
});

// Abre modal de agregar
const openAddModal = () => {
  Object.assign(form, {
    user_id: "",
    reserva_id: "",
    monto: "",
    metodo_pago: "",
    comprobante: null,
    fecha_pago: "",
    estado: "pendiente",
  });
  showAddModal.value = true;
};

// Abre modal de edición
const openEditModal = () => {
  if (!selectedPago.value) return alert("Selecciona un pago para modificar.");
  const pago = props.pagos.find((p) => p.id === selectedPago.value);
  Object.assign(form, {
    user_id: pago.user_id,
    reserva_id: pago.reserva_id,
    monto: pago.monto,
    metodo_pago: pago.metodo_pago,
    comprobante: null,
    fecha_pago: pago.fecha_pago,
    estado: pago.estado,
  });
  showEditModal.value = true;
};

// Crea un nuevo pago
const submitAdd = () => {
  const data = new FormData();
  for (let key in form) {
    if (form[key] !== null) data.append(key, form[key]);
  }

  router.post(route("admin.pagos.store"), data, {
    forceFormData: true,
    onSuccess: () => {
      alert("Pago registrado correctamente.");
      showAddModal.value = false;
      router.reload();
    },
    onError: (errors) => {
      alert("Error al registrar el pago.");
      console.error(errors);
    },
  });
};

// Actualiza un pago existente
const submitEdit = () => {
  if (!selectedPago.value) return;

  const data = new FormData();
  for (let key in form) {
    if (form[key] !== null) data.append(key, form[key]);
  }
  data.append("_method", "PUT"); // 👈 clave para que Laravel acepte el método

  router.post(route("admin.pagos.update", selectedPago.value), data, {
    forceFormData: true,
    onSuccess: () => {
      alert("Pago actualizado correctamente.");
      showEditModal.value = false;
      router.reload();
    },
    onError: (errors) => {
      alert("Error al actualizar pago.");
      console.error(errors);
    },
  });
};

// Elimina un pago
const deletePago = () => {
  if (!selectedPago.value) return alert("Selecciona un pago para eliminar.");
  if (confirm("¿Eliminar este pago?")) {
    router.delete(route("admin.pagos.destroy", selectedPago.value), {
      onSuccess: () => {
        alert("Pago eliminado correctamente.");
        router.reload();
      },
      onError: (errors) => {
        alert("Error al eliminar pago.");
        console.error(errors);
      },
    });
  }
};
</script>

<template>
  <Head title="Pagos" />
  <AdminLayout title="Pagos">
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">Lista de Pagos</h2>
    </template>

    <div class="py-6 px-4">
      <div class="flex gap-2 mb-4">
        <button class="bg-green-900 text-black px-3 py-1 rounded hover:bg-gray-700" @click="openAddModal">Agregar</button>
        <button class="bg-blue-600 text-black px-3 py-1 rounded hover:bg-gray-700" @click="openEditModal">Modificar</button>
        <button class="bg-gray-600 text-black px-3 py-1 rounded hover:bg-gray-700" @click="deletePago">Eliminar</button>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-2">#</th>
              <th class="px-4 py-2">Usuario</th>
              <th class="px-4 py-2">Reserva</th>
              <th class="px-4 py-2">Monto</th>
              <th class="px-4 py-2">Método</th>
              <th class="px-4 py-2">Fecha</th>
              <th class="px-4 py-2">Estado</th>
              <th class="px-4 py-2">Comprobante</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr
              v-for="pago in props.pagos"
              :key="pago.id"
              :class="selectedPago === pago.id ? 'bg-yellow-100' : ''"
              @click="selectedPago = pago.id"
            >
              <td class="px-4 py-2 text-center">
                <input type="radio" :value="pago.id" v-model="selectedPago" />
              </td>
              <td class="px-4 py-2">{{ pago.user?.name || '—' }}</td>
              <td class="px-4 py-2">#{{ pago.reserva_id }}</td>
              <td class="px-4 py-2">${{ pago.monto }}</td>
              <td class="px-4 py-2">{{ pago.metodo_pago }}</td>
              <td class="px-4 py-2">{{ pago.fecha_pago }}</td>
              <td class="px-4 py-2">
                <span
                  :class="{
                    'text-yellow-600': pago.estado === 'pendiente',
                    'text-green-600': pago.estado === 'confirmado',
                    'text-red-600': pago.estado === 'rechazado',
                  }"
                >
                  {{ pago.estado }}
                </span>
              </td>
              <td class="px-4 py-2">
                <a
                  v-if="pago.comprobante"
                  :href="`/storage/${pago.comprobante}`"
                  target="_blank"
                  class="text-blue-600 underline"
                  >Ver</a
                >
                <span v-else>—</span>
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
        <h3 class="text-lg font-semibold mb-4">Agregar Pago</h3>
        <form @submit.prevent="submitAdd" class="space-y-2">
          <select v-model="form.user_id" class="w-full border p-1" required>
            <option disabled value="">Seleccione usuario</option>
            <option v-for="u in props.usuarios" :key="u.id" :value="u.id">{{ u.name }}</option>
          </select>
          <select v-model="form.reserva_id" class="w-full border p-1" required>
            <option disabled value="">Seleccione reserva</option>
            <option v-for="r in props.reservas" :key="r.id" :value="r.id">Reserva #{{ r.id }}</option>
          </select>
          <input v-model="form.monto" type="number" placeholder="Monto" class="w-full border p-1" required />
          <input v-model="form.metodo_pago" placeholder="Método de pago" class="w-full border p-1" required />
          <input v-model="form.fecha_pago" type="datetime-local" class="w-full border p-1" required />
          <select v-model="form.estado" class="w-full border p-1" required>
            <option value="pendiente">Pendiente</option>
            <option value="confirmado">Confirmado</option>
            <option value="rechazado">Rechazado</option>
          </select>
          <input type="file" @change="e => (form.comprobante = e.target.files[0])" class="w-full border p-1" />

          <div class="flex justify-end gap-2 mt-2">
            <button type="button" class="bg-gray-400 text-black px-3 py-1 rounded" @click="showAddModal = false">
              Cancelar
            </button>
            <button type="submit" class="bg-green-600 text-black px-3 py-1 rounded hover:bg-green-500">
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
        <h3 class="text-lg font-semibold mb-4">Editar Pago</h3>
        <form @submit.prevent="submitEdit" class="space-y-2">
          <select v-model="form.user_id" class="w-full border p-1" required>
            <option disabled value="">Seleccione usuario</option>
            <option v-for="u in props.usuarios" :key="u.id" :value="u.id">{{ u.name }}</option>
          </select>
          <select v-model="form.reserva_id" class="w-full border p-1" required>
            <option disabled value="">Seleccione reserva</option>
            <option v-for="r in props.reservas" :key="r.id" :value="r.id">Reserva #{{ r.id }}</option>
          </select>
          <input v-model="form.monto" type="number" placeholder="Monto" class="w-full border p-1" required />
          <input v-model="form.metodo_pago" placeholder="Método de pago" class="w-full border p-1" required />
          <input v-model="form.fecha_pago" type="datetime-local" class="w-full border p-1" required />
          <select v-model="form.estado" class="w-full border p-1" required>
            <option value="pendiente">Pendiente</option>
            <option value="confirmado">Confirmado</option>
            <option value="rechazado">Rechazado</option>
          </select>
          <input type="file" @change="e => (form.comprobante = e.target.files[0])" class="w-full border p-1" />

          <div class="flex justify-end gap-2 mt-2">
            <button type="button" class="bg-gray-400 text-white px-3 py-1 rounded" @click="showEditModal = false">
              Cancelar
            </button>
            <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-500">
              Guardar
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>




