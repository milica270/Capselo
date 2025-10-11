<template>
  <div class="friend-card d-flex align-items-center p-1">
    <img
      :src="friend.avatar.startsWith('https') ? friend.avatar : `../storage/${friend.avatar}`" 
      alt="Friend photo"
      class="friend-img rounded me-2"
    />
    <span class="friend-name">{{ friend.name }}</span>
    <span class="friend-name ms-1">{{ friend.lastName }}</span>
  </div>
</template>

<script setup>
const props = defineProps({
  friend: Object,
})
</script>

<style scoped>
.friend-card {
  background-color: #fff; /* white background */
  border: none;
  border-radius: 6px;
  margin-bottom: 2px; /* minimal gap between cards */
}

.friend-img {
  width: 28px;   /* super small */
  height: 28px;
  object-fit: cover;
  border-radius: 6px;
}

.friend-name {
  font-size: 0.8rem; /* smaller text */
  color: var(--lightGrey);
  font-weight: 400;
  line-height: 1;
}
</style>


