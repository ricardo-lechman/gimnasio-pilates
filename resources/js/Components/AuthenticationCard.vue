<script setup></script>

<template>
  <div class="max-w-md mx-auto mt-10 sm:mt-20">
    <div class="flex justify-center mb-4">
      <slot name="logo" />
    </div>

    <div class="bg-white shadow-md rounded-lg px-6 py-8">
      <slot />
    </div>
  </div>
</template>
