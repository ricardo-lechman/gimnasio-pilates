<script setup>
import UsersLayout from '@/Layouts/UsersLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { reactive, ref, onMounted } from 'vue';

const props = defineProps({
  users: Array,
});

const showEditModal = ref(false);

const form = reactive({
  name: '',
  last_name: '',
  email: '',
  password: '',
  telefono: '',
  dni: '',
  obra_social: '',
  ficha_medica: '',
});

const errores = reactive({
  name: '',
  email: '',
  password: '',
  telefono: '',
  dni: '',
});

onMounted(() => {
  if (props.users.length > 0) {
    const user = props.users[0];
    Object.assign(form, {
      name: user.name,
      last_name: user.last_name || '',
      email: user.email,
      password: '',
      telefono: user.telefono || '',
      dni: user.dni || '',
      obra_social: user.obra_social || '',
      ficha_medica: user.ficha_medica || '',
    });
  }
});

const openEditModal = () => {
  Object.assign(errores, { name: '', email: '', password: '', telefono: '', dni: '' });
  showEditModal.value = true;
};

// Validación en el frontend
const validarCampos = () => {
  Object.assign(errores, { name: '', email: '', password: '', telefono: '', dni: '' });
  let valido = true;

  if (!form.name.trim()) {
    errores.name = 'Este campo es obligatorio';
    valido = false;
  }

  if (!form.email.trim()) {
    errores.email = 'Este campo es obligatorio';
    valido = false;
  }

  if (!form.dni.trim()) {
    errores.dni = 'El DNI es obligatorio';
    valido = false;
  } else if (!/^\d+$/.test(form.dni)) {
    errores.dni = 'El DNI solo puede contener números';
    valido = false;
  } else if (form.dni.length !== 8) {
    errores.dni = 'El DNI debe tener exactamente 8 dígitos';
    valido = false;
  }

  if (!form.telefono.trim()) {
    errores.telefono = 'El teléfono es obligatorio';
    valido = false;
  } else if (!/^\d+$/.test(form.telefono)) {
    errores.telefono = 'El teléfono solo puede contener números';
    valido = false;
  } else if (form.telefono.length !== 10) {
    errores.telefono = 'El teléfono debe tener exactamente 10 dígitos';
    valido = false;
  }

  if (form.password.trim() && form.password.length < 6) {
    errores.password = 'La contraseña debe tener al menos 6 caracteres';
    valido = false;
  }

  return valido;
};

// Guardar cambios
const submitEdit = () => {
  if (!validarCampos()) return;

  const userId = props.users[0].id;
  const data = {
    name: form.name,
    last_name: form.last_name,
    email: form.email,
    telefono: form.telefono,
    dni: form.dni,
    obra_social: form.obra_social,
    ficha_medica: form.ficha_medica,
  };

  if (form.password.trim() !== '') {
    data.password = form.password;
  }

  router.put(`/users/users/${userId}`, data, {
    onSuccess: () => {
      showEditModal.value = false;
      router.reload();
    },
    onError: (errors) => {
      // Captura de errores devueltos por Laravel
      if (errors.email) {
        errores.email = errors.email.includes('ya ha sido registrado')
          ? 'El email ya se encuentra registrado'
          : errors.email;
      }

      if (errors.dni) {
        errores.dni = errors.dni.includes('ya ha sido registrado')
          ? 'El DNI ya se encuentra registrado'
          : errors.dni;
      }
    },
  });
};

// Darse de baja
const darseDeBaja = () => {
  if (
    confirm('¿Estás seguro de que querés darte de baja? Esta acción eliminará tu cuenta permanentemente.')
  ) {
    const userId = props.users[0].id;
    router.delete(`/users/users/${userId}`, {
      onSuccess: () => router.visit('/'),
    });
  }
};
</script>

<template>
  <Head title="Tus datos" />
  <UsersLayout title="Tus datos">
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">Tus datos</h2>
    </template>

    <div class="py-6 px-4">
      <!-- Botones -->
      <div class="flex gap-2 mb-4">
        <button class="px-3 py-1 rounded text-black" @click="openEditModal">Modificar</button>
        <button class="px-3 py-1 rounded text-black" @click="darseDeBaja">Darse de baja</button>
      </div>

      <!-- Tabla -->
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
          <div>
            <input v-model="form.name" placeholder="Nombre" class="w-full border p-1" />
            <p v-if="errores.name" class="text-red-600 text-sm">{{ errores.name }}</p>
          </div>

          <input v-model="form.last_name" placeholder="Apellido" class="w-full border p-1" />

          <div>
            <input v-model="form.email" placeholder="Email" type="email" class="w-full border p-1" />
            <p v-if="errores.email" class="text-red-600 text-sm">{{ errores.email }}</p>
          </div>

          <div>
            <input
              v-model="form.password"
              type="password"
              placeholder="Nueva Contraseña (opcional)"
              class="w-full border p-1"
            />
            <p v-if="errores.password" class="text-red-600 text-sm">{{ errores.password }}</p>
          </div>

          <div>
            <input v-model="form.telefono" placeholder="Teléfono" class="w-full border p-1" />
            <p v-if="errores.telefono" class="text-red-600 text-sm">{{ errores.telefono }}</p>
          </div>

          <div>
            <input v-model="form.dni" placeholder="DNI" class="w-full border p-1" />
            <p v-if="errores.dni" class="text-red-600 text-sm">{{ errores.dni }}</p>
          </div>

          <input v-model="form.obra_social" placeholder="Obra Social" class="w-full border p-1" />
          <textarea v-model="form.ficha_medica" placeholder="Ficha Médica" class="w-full border p-1"></textarea>

          <div class="flex justify-end gap-2 mt-2">
            <button type="button" class="px-3 py-1 rounded text-black" @click="showEditModal = false">
              Cancelar
            </button>
            <button type="submit" class="px-3 py-1 rounded text-black">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </UsersLayout>
</template>
