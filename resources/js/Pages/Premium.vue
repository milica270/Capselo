<template>
  <div>
    
    <div v-if="show" class="popup">
      Coming soon
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const show = ref(true) // ako želiš da se odmah prikaže
</script>

<style scoped>
.popup {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background: white;
  padding: 10px 20px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 18px;
}
</style>
