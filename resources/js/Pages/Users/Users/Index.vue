<script setup>
import UsersLayout from '@/Layouts/UsersLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { reactive, ref, onMounted } from 'vue';

const props = defineProps({
  users: Array,  // Se espera un array con un solo usuario
});

const showEditModal = ref(false);

const form = reactive({
  nombre: '',
  email: '',
  password: '',
});


onMounted(() => {
  if (props.users.length > 0) {
    const user = props.users[0];
    form.nombre = user.name;
    form.email = user.email;
    form.password = '';
  }
});

const openEditModal = () => {
  showEditModal.value = true;
};

const submitEdit = () => {
  const userId = props.users[0].id;
  const data = {
    nombre: form.nombre,
    email: form.email,
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
    }
  });
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
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-2">ID</th>
              <th class="px-4 py-2">Nombre</th>
              <th class="px-4 py-2">Email</th>
              <th class="px-4 py-2">Rol</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr>
              <td class="px-4 py-2">{{ props.users[0].id }}</td>
              <td class="px-4 py-2">{{ props.users[0].name }}</td>
              <td class="px-4 py-2">{{ props.users[0].email }}</td>
              <td class="px-4 py-2">{{ props.users[0].role }}</td>
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
      <div class="bg-white p-6 rounded w-96">
        <h3 class="text-lg font-semibold mb-4">Editar Usuario</h3>
        <form @submit.prevent="submitEdit" class="space-y-2">
          <input
            v-model="form.nombre"
            placeholder="Nombre"
            class="w-full border p-1"
            required
          />
          <input
            v-model="form.email"
            placeholder="Email"
            type="email"
            class="w-full border p-1"
            required
          />
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
