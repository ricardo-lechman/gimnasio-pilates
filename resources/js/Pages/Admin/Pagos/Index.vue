<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";

defineProps({
  pagos: Array,
  usuarios: Array,
  reservas: Array,
});

const form = ref({
  user_id: "",
  reserva_id: "",
  monto: "",
  metodo_pago: "",
  comprobante: null,
  fecha_pago: "",
  estado: "pendiente",
});

function submit() {
  if (!form.value.user_id || !form.value.reserva_id || !form.value.monto || !form.value.metodo_pago || !form.value.fecha_pago) {
    alert("Todos los campos obligatorios deben completarse");
    return;
  }

  const data = new FormData();
  for (let key in form.value) {
    data.append(key, form.value[key]);
  }

  router.post(route("admin.pagos.store"), data, {
    forceFormData: true,
    onSuccess: () => {
      Object.assign(form.value, {
        user_id: "",
        reserva_id: "",
        monto: "",
        metodo_pago: "",
        comprobante: null,
        fecha_pago: "",
        estado: "pendiente",
      });
    },
  });
}

function update(pago) {
  const data = new FormData();
  for (let key in pago) {
    data.append(key, pago[key]);
  }
  router.post(route("admin.pagos.update", pago.id), data, {
    forceFormData: true,
    method: "put",
  });
}

function destroy(id) {
  if (confirm("¿Eliminar este pago?")) {
    router.delete(route("admin.pagos.destroy", id));
  }
}
</script>

<template>
  <AdminLayout title="Gestión de Pagos">
    <h1 class="text-2xl font-bold mb-4">Gestión de Pagos</h1>

    <!-- Formulario nuevo pago -->
    <form @submit.prevent="submit" class="mb-6 flex flex-wrap gap-2 items-center">
      <!-- Usuario -->
      <select v-model="form.user_id" class="border p-2 rounded">
        <option disabled value="">Seleccione usuario</option>
        <option v-for="u in usuarios" :key="u.id" :value="u.id">
          {{ u.name }}
        </option>
      </select>

      <!-- Reserva -->
      <select v-model="form.reserva_id" class="border p-2 rounded">
        <option disabled value="">Seleccione reserva</option>
        <option v-for="r in reservas" :key="r.id" :value="r.id">
          Reserva #{{ r.id }}
        </option>
      </select>

      <input type="number" v-model="form.monto" class="border p-2 rounded" placeholder="Monto" />
      <input type="text" v-model="form.metodo_pago" class="border p-2 rounded" placeholder="Método de pago" />
      <input type="datetime-local" v-model="form.fecha_pago" class="border p-2 rounded" />

      <select v-model="form.estado" class="border p-2 rounded">
        <option value="pendiente">Pendiente</option>
        <option value="confirmado">Confirmado</option>
        <option value="rechazado">Rechazado</option>
      </select>

      <input type="file" @change="e => form.comprobante = e.target.files[0]" class="border p-2 rounded" />

      <button class="bg-blue-500 text-white px-4 py-2 rounded">Agregar</button>
    </form>

    <!-- Listado -->
    <table class="w-full border rounded overflow-hidden">
      <thead>
        <tr class="bg-gray-200 text-left">
          <th class="p-2">ID</th>
          <th class="p-2">Usuario</th>
          <th class="p-2">Reserva</th>
          <th class="p-2">Monto</th>
          <th class="p-2">Método</th>
          <th class="p-2">Fecha</th>
          <th class="p-2">Estado</th>
          <th class="p-2">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="pago in pagos" :key="pago.id" class="border-t">
          <td class="p-2">{{ pago.id }}</td>
          <td class="p-2">{{ pago.user?.name }}</td>
          <td class="p-2">#{{ pago.reserva_id }}</td>
          <td class="p-2"><input type="number" v-model="pago.monto" class="border p-1 rounded w-full" /></td>
          <td class="p-2"><input type="text" v-model="pago.metodo_pago" class="border p-1 rounded w-full" /></td>
          <td class="p-2"><input type="datetime-local" v-model="pago.fecha_pago" class="border p-1 rounded w-full" /></td>
          <td class="p-2">
            <select v-model="pago.estado" class="border p-1 rounded">
              <option value="pendiente">Pendiente</option>
              <option value="confirmado">Confirmado</option>
              <option value="rechazado">Rechazado</option>
            </select>
          </td>
          <td class="p-2 flex gap-2">
            <button @click="update(pago)" class="bg-green-500 text-white px-2 py-1 rounded">Guardar</button>
            <button @click="destroy(pago.id)" class="bg-red-500 text-white px-2 py-1 rounded">Eliminar</button>
          </td>
        </tr>
      </tbody>
    </table>
  </AdminLayout>
</template>

