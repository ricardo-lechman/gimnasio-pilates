<script setup>
import UserLayout from "@/Layouts/UsersLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref, reactive } from "vue";

const props = defineProps({
  pagos: Array,
  reservas: Array,
  user: Object,
});

const showAddModal = ref(false);

const form = reactive({
  reserva_id: "",
  monto: "",
  metodo_pago: "",
  comprobante: null,
});

// Abrir modal
const openAddModal = () => {
  Object.assign(form, {
    reserva_id: "",
    monto: "",
    metodo_pago: "",
    comprobante: null,
  });
  showAddModal.value = true;
};

// Enviar formulario
const submitAdd = () => {
  const data = new FormData();
  for (let key in form) {
    if (form[key] !== null) data.append(key, form[key]);
  }

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
              <td class="px-4 py-2">
                Reserva #{{ pago.reserva_id }}
              </td>
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

    <!-- Modal Agregar -->
    <div
      v-if="showAddModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
      <div class="bg-white p-6 rounded w-96">
        <h3 class="text-lg font-semibold mb-4">Agregar Pago</h3>
        <form @submit.prevent="submitAdd" class="space-y-2">
          <select v-model="form.reserva_id" class="w-full border p-1" required>
            <option disabled value="">Seleccione una reserva</option>
            <option
              v-for="r in props.reservas"
              :key="r.id"
              :value="r.id"
            >
              Reserva #{{ r.id }}
            </option>
          </select>

          <input
            v-model="form.monto"
            type="number"
            placeholder="Monto"
            class="w-full border p-1"
            required
          />

          <input
            v-model="form.metodo_pago"
            placeholder="Método de pago (Ej: Transferencia, Efectivo)"
            class="w-full border p-1"
            required
          />

          <input
            type="file"
            @change="e => (form.comprobante = e.target.files[0])"
            class="w-full border p-1"
            required
          />

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





