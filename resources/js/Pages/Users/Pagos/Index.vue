<script setup>
import UsersLayout from "@/Layouts/UsersLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref, reactive } from "vue";

const props = defineProps({
  pagos: Array,
  reservas: Array,
});

const selectedPago = ref(null);

const showAddModal = ref(false);
const showEditModal = ref(false);

const form = reactive({
  reserva_id: "",
  monto: "",
  metodo_pago: "",
  comprobante: null,
  fecha_pago: "",
  estado: "pendiente",
});

const openAddModal = () => {
  Object.assign(form, {
    reserva_id: "",
    monto: "",
    metodo_pago: "",
    comprobante: null,
    fecha_pago: "",
    estado: "pendiente",
  });
  showAddModal.value = true;
};

const openEditModal = () => {
  if (!selectedPago.value)
    return alert("Selecciona un pago para modificar.");

  const pago = props.pagos.find((p) => p.id === selectedPago.value);
  Object.assign(form, {
    reserva_id: pago.reserva_id,
    monto: pago.monto,
    metodo_pago: pago.metodo_pago,
    comprobante: null, // se setea solo si sube otro
    fecha_pago: pago.fecha_pago,
    estado: pago.estado,
  });
  showEditModal.value = true;
};

const submitAdd = () => {
  const data = new FormData();
  Object.entries(form).forEach(([key, value]) => {
    if (value !== null) data.append(key, value);
  });

  router.post(route("users.pagos.store"), data, {
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

const submitEdit = () => {
  const data = new FormData();
  Object.entries(form).forEach(([key, value]) => {
    if (value !== null) data.append(key, value);
  });

  router.post(route("users.pagos.update", selectedPago.value), data, {
    method: "put",
    forceFormData: true,
    onSuccess: () => {
      alert("Pago actualizado correctamente.");
      showEditModal.value = false;
      router.reload();
    },
    onError: (errors) => {
      alert("Error al actualizar el pago.");
      console.error(errors);
    },
  });
};

const deletePago = () => {
  if (!selectedPago.value)
    return alert("Selecciona un pago para eliminar.");
  if (confirm("¿Eliminar este pago?")) {
    router.delete(route("users.pagos.destroy", selectedPago.value), {
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
  <UsersLayout title="Pagos">
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Lista de Pagos
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
          @click="deletePago"
        >
          Eliminar
        </button>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-2">Seleccionar</th>
              <th class="px-4 py-2">ID</th>
              <th class="px-4 py-2">Reserva</th>
              <th class="px-4 py-2">Monto</th>
              <th class="px-4 py-2">Método</th>
              <th class="px-4 py-2">Comprobante</th>
              <th class="px-4 py-2">Fecha</th>
              <th class="px-4 py-2">Estado</th>
            </tr>
          </thead>
          <tbody class="bg-black divide-y divide-gray-200">
            <tr
              v-for="pago in props.pagos"
              :key="pago.id"
              :class="selectedPago === pago.id ? 'bg-yellow-100' : ''"
              @click="selectedPago = pago.id"
            >
              <td class="px-4 py-2 text-center">
                <input type="radio" :value="pago.id" v-model="selectedPago" />
              </td>
              <td class="px-4 py-2">{{ pago.id }}</td>
              <td class="px-4 py-2">{{ pago.reserva?.fecha }}</td>
              <td class="px-4 py-2">${{ pago.monto }}</td>
              <td class="px-4 py-2">{{ pago.metodo_pago }}</td>
              <td class="px-4 py-2">
                <a
                  v-if="pago.comprobante"
                  :href="`/storage/${pago.comprobante}`"
                  target="_blank"
                  class="text-blue-600 underline"
                >
                  Ver
                </a>
              </td>
              <td class="px-4 py-2">{{ pago.fecha_pago }}</td>
              <td class="px-4 py-2">{{ pago.estado }}</td>
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
        <h3 class="text-lg font-semibold mb-4">Agregar Pago</h3>
        <form @submit.prevent="submitAdd" class="space-y-2">
          <select v-model="form.reserva_id" class="w-full border p-1" required>
            <option disabled value="">Seleccione una reserva</option>
            <option v-for="r in props.reservas" :key="r.id" :value="r.id">
              {{ r.fecha }}
            </option>
          </select>
          <input
            v-model="form.monto"
            type="number"
            step="0.01"
            class="w-full border p-1"
            placeholder="Monto"
            required
          />
          <input
            v-model="form.metodo_pago"
            type="text"
            class="w-full border p-1"
            placeholder="Método de pago"
            required
          />
          <input
            type="file"
            class="w-full border p-1"
            @change="form.comprobante = $event.target.files[0]"
          />
          <input
            v-model="form.fecha_pago"
            type="date"
            class="w-full border p-1"
            required
          />
          <select v-model="form.estado" class="w-full border p-1" required>
            <option value="pendiente">Pendiente</option>
            <option value="confirmado">Confirmado</option>
            <option value="rechazado">Rechazado</option>
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
              class="bg-black text-black px-3 py-1 rounded hover:bg-gray-800"
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
        <h3 class="text-lg font-semibold mb-4">Editar Pago</h3>
        <form @submit.prevent="submitEdit" class="space-y-2">
          <select v-model="form.reserva_id" class="w-full border p-1" required>
            <option disabled value="">Seleccione una reserva</option>
            <option v-for="r in props.reservas" :key="r.id" :value="r.id">
              {{ r.fecha }}
            </option>
          </select>
          <input
            v-model="form.monto"
            type="number"
            step="0.01"
            class="w-full border p-1"
            placeholder="Monto"
            required
          />
          <input
            v-model="form.metodo_pago"
            type="text"
            class="w-full border p-1"
            placeholder="Método de pago"
            required
          />
          <input
            type="file"
            class="w-full border p-1"
            @change="form.comprobante = $event.target.files[0]"
          />
          <input
            v-model="form.fecha_pago"
            type="date"
            class="w-full border p-1"
            required
          />
          <select v-model="form.estado" class="w-full border p-1" required>
            <option value="pendiente">Pendiente</option>
            <option value="confirmado">Confirmado</option>
            <option value="rechazado">Rechazado</option>
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
              class="bg-black text-black px-3 py-1 rounded hover:bg-gray-800"
            >
              Guardar
            </button>
          </div>
        </form>
      </div>
    </div>
  </UsersLayout>
</template>

