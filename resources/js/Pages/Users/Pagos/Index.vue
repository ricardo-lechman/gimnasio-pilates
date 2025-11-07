<script setup>
import UserLayout from "@/Layouts/UsersLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref, reactive, watch } from "vue";

const props = defineProps({
  pagos: Array,
  reservas: Array,
  user: Object,
});

const showAddModal = ref(false);

const form = reactive({
  reserva_id: null,
  metodo_pago: "",
  comprobante: null,
  monto: "0.00",
});

// Abrir modal
const openAddModal = () => {
  Object.assign(form, {
    reserva_id: null,
    metodo_pago: "",
    comprobante: null,
    monto: "0.00",
  });
  showAddModal.value = true;
};

watch(
  () => form.reserva_id,
  (newVal) => {
    const reserva = props.reservas.find(r => r.id === newVal);
    if (reserva && typeof reserva.monto === "number") {
      form.monto = reserva.monto.toFixed(2);
    } else {
      form.monto = "3.500";
    }
  }
);

// Enviar formulario
const submitAdd = () => {
  const data = new FormData();
  data.append('reserva_id', form.reserva_id);
  data.append('metodo_pago', form.metodo_pago);
  if (form.comprobante) data.append('comprobante', form.comprobante);

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
</script>

<template>
  <Head title="Mis Pagos" />
  <UserLayout title="Mis Pagos">
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">Mis Pagos</h2>
    </template>

    <div class="py-6 px-4">
      <div class="flex gap-2 mb-4">
        <button
          class="bg-green-600 text-black px-3 py-1 rounded hover:bg-green-500"
          @click="openAddModal"
        >
          Agregar Pago
        </button>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-2">#</th>
              <th class="px-4 py-2">Reserva</th>
              <th class="px-4 py-2">Monto</th>
              <th class="px-4 py-2">Método</th>
              <th class="px-4 py-2">Comprobante</th>
              <th class="px-4 py-2">Estado</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="pago in props.pagos" :key="pago.id">
              <td class="px-4 py-2">#{{ pago.id }}</td>
              <td class="px-4 py-2">Reserva #{{ pago.reserva_id }}</td>
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
                <span v-else>—</span>
              </td>
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
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal Agregar Pago -->
    <div
      v-if="showAddModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
      <div class="bg-white p-6 rounded w-96">
        <h3 class="text-lg font-semibold mb-4">Agregar Pago</h3>
        <form @submit.prevent="submitAdd" class="space-y-2">

          <select v-model="form.reserva_id" class="w-full border p-1 text-gray-700" required>
            <option disabled value="null" selected hidden>Seleccione una reserva</option>
            <option
              v-for="r in props.reservas"
              :key="r.id"
              :value="String(r.id)"
            >
              Reserva #{{ r.id }}
            </option>
          </select>

          <!-- Monto mostrado (solo lectura) -->
          <input
            type="text"
            :value="`$${form.monto}`"
            class="w-full border p-1 bg-gray-100 cursor-not-allowed"
            readonly
          />

          <!-- Método de pago con botones -->
          <div class="flex gap-2">
            <button
              type="button"
              :class="form.metodo_pago === 'efectivo' ? 'bg-green-600 text-black' : 'bg-gray-200 text-black'"
              class="px-3 py-1 rounded flex-1"
              @click="form.metodo_pago = 'efectivo'"
            >
              Efectivo
            </button>
            <button
              type="button"
              :class="form.metodo_pago === 'transferencia' ? 'bg-green-600 text-black' : 'bg-gray-200 text-black'"
              class="px-3 py-1 rounded flex-1"
              @click="form.metodo_pago = 'transferencia'"
            >
              Transferencia
            </button>
          </div>

          <!-- Comprobante -->
          <input
            type="file"
            @change="e => (form.comprobante = e.target.files[0])"
            class="w-full border p-1"
            required
          />

          <!-- Botones -->
          <div class="flex justify-end gap-2 mt-2">
            <button
              type="button"
              class="bg-gray-400 text-black px-3 py-1 rounded"
              @click="showAddModal = false"
            >
              Cancelar
            </button>
            <button
              type="submit"
              class="bg-green-600 text-black px-3 py-1 rounded hover:bg-green-500"
            >
              Agregar
            </button>
          </div>
        </form>
      </div>
    </div>
  </UserLayout>
</template>
