<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, reactive } from 'vue';

defineProps({ users: Array });

// Usuario seleccionado
const selectedUser = ref(null);

// Modales
const showAddModal = ref(false);
const showEditModal = ref(false);

// Formulario reactivo
const form = reactive({
  nombre: '',
  email: '',
  password: '',
});

// Abrir modal agregar
const openAddModal = () => {
  Object.assign(form, { nombre: '', email: '', password: '' });
  showAddModal.value = true;
};

// Abrir modal editar
const openEditModal = () => {
  if (!selectedUser.value) return alert('Selecciona un usuario para modificar.');
  const user = users.find(u => u.id === selectedUser.value);
  Object.assign(form, { nombre: user.name, email: user.email, password: '' });
  showEditModal.value = true;
};

// Agregar usuario
const submitAdd = () => {
  router.post('/admin/users', { ...form }).then(() => { router.reload(); showAddModal.value = false });
};

// Editar usuario
const submitEdit = () => {
  router.put(`/admin/users/${selectedUser.value}`, { ...form }).then(() => { router.reload(); showEditModal.value = false });
};

// Eliminar usuario
const deleteUser = () => {
  if (!selectedUser.value) return alert('Selecciona un usuario para eliminar.');
  router.delete(`/admin/users/${selectedUser.value}`).then(() => router.reload());
};
</script>

<template>
  <Head title="Usuarios" />
  <AdminLayout title="Usuarios">
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Lista de Usuarios
      </h2>
    </template>

    <div class="py-6 px-4">

      <!-- Botones principales -->
      <div class="flex gap-2 mb-4">
        <button class="bg-black text-black px-3 py-1 rounded hover:bg-gray-800" @click="openAddModal">Agregar</button>
        <button class="bg-black text-black px-3 py-1 rounded hover:bg-gray-800" @click="openEditModal">Modificar</button>
        <button class="bg-black text-black px-3 py-1 rounded hover:bg-gray-800" @click="deleteUser">Eliminar</button>
      </div>

      <!-- Tabla -->
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-2">Seleccionar</th>
              <th class="px-4 py-2">ID</th>
              <th class="px-4 py-2">Nombre</th>
              <th class="px-4 py-2">Email</th>
              <th class="px-4 py-2">Rol</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="user in users" :key="user.id" :class="selectedUser === user.id ? 'bg-yellow-100' : ''" @click="selectedUser = user.id">
              <td class="px-4 py-2 text-center"><input type="radio" :value="user.id" v-model="selectedUser" /></td>
              <td class="px-4 py-2">{{ user.id }}</td>
              <td class="px-4 py-2">{{ user.name }}</td>
              <td class="px-4 py-2">{{ user.email }}</td>
              <td class="px-4 py-2">{{ user.role }}</td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>

    <!-- Modal Agregar -->
    <div v-if="showAddModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded w-96">
        <h3 class="text-lg font-semibold mb-4">Agregar Usuario</h3>
        <form @submit.prevent="submitAdd" class="space-y-2">
          <input v-model="form.nombre" placeholder="Nombre" class="w-full border p-1" required />
          <input v-model="form.email" placeholder="Email" type="email" class="w-full border p-1" required />
          <input v-model="form.password" type="password" placeholder="Password" class="w-full border p-1" required />
          <div class="flex justify-end gap-2 mt-2">
            <button type="button" class="bg-black text-black px-3 py-1 rounded hover:bg-gray-800" @click="showAddModal = false">Cancelar</button>
            <button type="submit" class="bg-black text-black px-3 py-1 rounded hover:bg-gray-800">Agregar</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal Editar -->
    <div v-if="showEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded w-96">
        <h3 class="text-lg font-semibold mb-4">Editar Usuario</h3>
        <form @submit.prevent="submitEdit" class="space-y-2">
          <input v-model="form.nombre" placeholder="Nombre" class="w-full border p-1" required />
          <input v-model="form.email" placeholder="Email" type="email" class="w-full border p-1" required />
          <div class="flex justify-end gap-2 mt-2">
            <button type="button" class="bg-black text-black px-3 py-1 rounded hover:bg-gray-800" @click="showEditModal = false">Cancelar</button>
            <button type="submit" class="bg-black text-black px-3 py-1 rounded hover:bg-gray-800">Guardar</button>
          </div>
        </form>
      </div>
    </div>

  </AdminLayout>
</template>
