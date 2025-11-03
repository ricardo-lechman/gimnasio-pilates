<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, reactive } from 'vue';

const props = defineProps({
  users: Array,
});

const selectedUser = ref(null);
const showAddModal = ref(false);
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
  role: 'user',
});

const resetForm = () => {
  Object.assign(form, {
    name: '',
    last_name: '',
    email: '',
    password: '',
    telefono: '',
    dni: '',
    obra_social: '',
    ficha_medica: '',
    role: 'user',
  });
};

const openAddModal = () => {
  resetForm();
  showAddModal.value = true;
};

const openEditModal = () => {
  if (!selectedUser.value) return alert('Selecciona un usuario para modificar.');
  const user = props.users.find(u => u.id === selectedUser.value);
  Object.assign(form, {
    name: user.name,
    last_name: user.last_name || '',
    email: user.email,
    password: '',
    telefono: user.telefono || '',
    dni: user.dni || '',
    obra_social: user.obra_social || '',
    ficha_medica: user.ficha_medica || '',
    role: user.role || 'user',
  });
  showEditModal.value = true;
};

const submitAdd = () => {
  router.post('/admin/users', { ...form }, {
    onSuccess: () => {
      alert('Usuario creado correctamente.');
      showAddModal.value = false;
      router.reload();
    },
    onError: () => alert('Error al crear usuario.')
  });
};

const submitEdit = () => {
  const data = { ...form };
  if (form.password.trim() === '') delete data.password;

  router.put(`/admin/users/${selectedUser.value}`, data, {
    onSuccess: () => {
      alert('Usuario actualizado correctamente.');
      showEditModal.value = false;
      router.reload();
    },
    onError: () => alert('Error al actualizar usuario.')
  });
};

const deleteUser = () => {
  if (!selectedUser.value) return alert('Selecciona un usuario para eliminar.');
  if (!confirm('¿Seguro que quieres eliminar este usuario?')) return;

  router.delete(`/admin/users/${selectedUser.value}`, {
    onSuccess: () => {
      alert('Usuario eliminado correctamente.');
      router.reload();
    },
    onError: () => alert('Error al eliminar usuario.')
  });
};
</script>

<template>
  <Head title="Usuarios" />
  <AdminLayout title="Usuarios">
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">Lista de Usuarios</h2>
    </template>

    <div class="py-6 px-4">
      <!-- Botones de acción -->
      <div class="flex gap-2 mb-4">
        <button class="px-3 py-1 rounded text-black" @click="openAddModal">Agregar</button>
        <button class="px-3 py-1 rounded text-black" @click="openEditModal">Modificar</button>
        <button class="px-3 py-1 rounded text-black" @click="deleteUser">Eliminar</button>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-2">Seleccionar</th>
              <th class="px-4 py-2">ID</th>
              <th class="px-4 py-2">Nombre</th>
              <th class="px-4 py-2">Apellido</th>
              <th class="px-4 py-2">Email</th>
              <th class="px-4 py-2">Rol</th>
              <th class="px-4 py-2">Teléfono</th>
              <th class="px-4 py-2">DNI</th>
              <th class="px-4 py-2">Obra Social</th>
              <th class="px-4 py-2">Ficha Médica</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr
              v-for="user in props.users"
              :key="user.id"
              :class="selectedUser === user.id ? 'bg-yellow-100' : ''"
              @click="selectedUser = user.id"
            >
              <td class="px-4 py-2 text-center">
                <input type="radio" :value="user.id" v-model="selectedUser" />
              </td>
              <td class="px-4 py-2">{{ user.id }}</td>
              <td class="px-4 py-2">{{ user.name }}</td>
              <td class="px-4 py-2">{{ user.last_name }}</td>
              <td class="px-4 py-2">{{ user.email }}</td>
              <td class="px-4 py-2">{{ user.role }}</td>
              <td class="px-4 py-2">{{ user.telefono }}</td>
              <td class="px-4 py-2">{{ user.dni }}</td>
              <td class="px-4 py-2">{{ user.obra_social }}</td>
              <td class="px-4 py-2">{{ user.ficha_medica }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal Agregar -->
    <div v-if="showAddModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded w-96 overflow-y-auto max-h-[90vh]">
        <h3 class="text-lg font-semibold mb-4">Agregar Usuario</h3>
        <form @submit.prevent="submitAdd" class="space-y-2">
          <input v-model="form.name" placeholder="Nombre" class="w-full border p-1" required />
          <input v-model="form.last_name" placeholder="Apellido" class="w-full border p-1" />
          <input v-model="form.email" placeholder="Email" type="email" class="w-full border p-1" required />
          <input v-model="form.password" type="password" placeholder="Contraseña" class="w-full border p-1" required />
          <input v-model="form.telefono" placeholder="Teléfono" class="w-full border p-1" />
          <input v-model="form.dni" placeholder="DNI" class="w-full border p-1" />
          <input v-model="form.obra_social" placeholder="Obra Social" class="w-full border p-1" />
          <textarea v-model="form.ficha_medica" placeholder="Ficha Médica" class="w-full border p-1"></textarea>
          <div class="flex justify-end gap-2 mt-2">
            <button type="button" class="px-3 py-1 rounded text-black" @click="showAddModal = false">Cancelar</button>
            <button type="submit" class="px-3 py-1 rounded text-black">Agregar</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal Editar -->
    <div v-if="showEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded w-96 overflow-y-auto max-h-[90vh]">
        <h3 class="text-lg font-semibold mb-4">Editar Usuario</h3>
        <form @submit.prevent="submitEdit" class="space-y-2">
          <input v-model="form.name" placeholder="Nombre" class="w-full border p-1" required />
          <input v-model="form.last_name" placeholder="Apellido" class="w-full border p-1" />
          <input v-model="form.email" placeholder="Email" type="email" class="w-full border p-1" required />
          <input v-model="form.password" type="password" placeholder="Nueva Contraseña (opcional)" class="w-full border p-1" />
          <input v-model="form.telefono" placeholder="Teléfono" class="w-full border p-1" />
          <input v-model="form.dni" placeholder="DNI" class="w-full border p-1" />
          <input v-model="form.obra_social" placeholder="Obra Social" class="w-full border p-1" />
          <textarea v-model="form.ficha_medica" placeholder="Ficha Médica" class="w-full border p-1"></textarea>
          <div class="flex justify-end gap-2 mt-2">
            <button type="button" class="px-3 py-1 rounded text-black" @click="showEditModal = false">Cancelar</button>
            <button type="submit" class="px-3 py-1 rounded text-black">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>
