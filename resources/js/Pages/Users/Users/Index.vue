<script setup>
import UsersLayout from '@/Layouts/UsersLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { reactive, ref, onMounted } from 'vue';

const props = defineProps({
  users: Array, // Se espera un array con un solo usuario
});

const showEditModal = ref(false);

const form = reactive({
  name: '',
  last_name: '',
  email: '',
  password: '',
  dni: '',
  telefono: '',
  obra_social: '',
  ficha_medica: '',
});

onMounted(() => {
  if (props.users.length > 0) {
    const user = props.users[0];
    form.name = user.name;
    form.last_name = user.last_name || '';
    form.email = user.email;
    form.password = '';
    form.dni = user.dni || '';
    form.telefono = user.telefono || '';
    form.obra_social = user.obra_social || '';
    form.ficha_medica = user.ficha_medica || '';
  }
});

const openEditModal = () => {
  showEditModal.value = true;
};

const submitEdit = () => {
  const userId = props.users[0].id;
  const data = {
    name: form.name,
    last_name: form.last_name,
    email: form.email,
    dni: form.dni,
    telefono: form.telefono,
    obra_social: form.obra_social,
    ficha_medica: form.ficha_medica,
  };

  if (form.password.trim() !== '') {
    data.password = form.password;
  }

  router.put(`/users/users/${userId}`, data, {
    onSuccess: () => {
      alert('Usuario actualizado correctamente.');
      showEditModal.value = false;
      router.reload();
    },
    onError: (errors) => {
      alert('Error al actualizar usuario.');
      console.error(errors);
    },
  });
};

//Darse de baja
const darseDeBaja = () => {
  if (
    confirm(
      '¿Estás seguro de que querés darte de baja? Esta acción eliminará tu cuenta permanentemente.'
    )
  ) {
    const userId = props.users[0].id;

    router.delete(`/users/users/${userId}`, {
      onSuccess: () => {
        alert('Tu cuenta ha sido eliminada.');
        router.visit('/'); // Redirige a la página de inicio o login
      },
      onError: (errors) => {
        alert('Error al eliminar tu cuenta.');
        console.error(errors);
      },
    });
  }
};
</script>

<template>
  <Head title="Tus datos" />
  <UsersLayout title="Tus datos">
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Tus datos
      </h2>
    </template>

    <div class="py-6 px-4">
      <div class="flex gap-2 mb-4">
        <button
          class="bg-black text-black px-3 py-1 rounded hover:bg-gray-800"
          @click="openEditModal"
        >
          Modificar
        </button>
        <button
          class="bg-red-600 text-black px-3 py-1 rounded hover:bg-red-700"
          @click="darseDeBaja"
        >
          Darse de baja
        </button>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-2">ID</th>
              <th class="px-4 py-2">Nombre</th>
              <th class="px-4 py-2">Apellido</th>
              <th class="px-4 py-2">Email</th>
              <th class="px-4 py-2">DNI</th>
              <th class="px-4 py-2">Teléfono</th>
              <th class="px-4 py-2">Obra Social</th>
              <th class="px-4 py-2">Ficha Médica</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr>
              <td class="px-4 py-2">{{ props.users[0].id }}</td>
              <td class="px-4 py-2">{{ props.users[0].name }}</td>
              <td class="px-4 py-2">{{ props.users[0].last_name }}</td>
              <td class="px-4 py-2">{{ props.users[0].email }}</td>
              <td class="px-4 py-2">{{ props.users[0].dni }}</td>
              <td class="px-4 py-2">{{ props.users[0].telefono }}</td>
              <td class="px-4 py-2">{{ props.users[0].obra_social }}</td>
              <td class="px-4 py-2">{{ props.users[0].ficha_medica }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal Editar -->
    <div
      v-if="showEditModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
      <div class="bg-white p-6 rounded w-96 overflow-y-auto max-h-[90vh]">
        <h3 class="text-lg font-semibold mb-4">Editar tus datos</h3>
        <form @submit.prevent="submitEdit" class="space-y-2">
          <input
            v-model="form.name"
            placeholder="Nombre"
            class="w-full border p-1"
            required
          />
          <input
            v-model="form.last_name"
            placeholder="Apellido"
            class="w-full border p-1"
          />
          <input
            v-model="form.email"
            placeholder="Email"
            type="email"
            class="w-full border p-1"
            required
          />
          <input
            v-model="form.dni"
            placeholder="DNI"
            type="number"
            class="w-full border p-1"
            required
          />
          <input
            v-model="form.telefono"
            placeholder="Teléfono"
            type="number"
            class="w-full border p-1"
            required
          />
          <input
            v-model="form.obra_social"
            placeholder="Obra Social"
            class="w-full border p-1"
          />
          <textarea
            v-model="form.ficha_medica"
            placeholder="Ficha Médica"
            class="w-full border p-1"
          ></textarea>
          <input
            v-model="form.password"
            type="password"
            placeholder="Nueva Contraseña (opcional)"
            class="w-full border p-1"
          />
          <div class="flex justify-end gap-2 mt-2">
            <button
              type="button"
              class="bg-black text-black px-3 py-1 rounded hover:bg-gray-800"
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
