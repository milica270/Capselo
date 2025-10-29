<template>
  <div class="container py-4">
    <h2 class="fw-bold text-success mb-3">
      <span style="color: var(--darkGrey)">Your Capsules from: </span>{{ formattedDate }}
    </h2>

    <div v-if="capsules.length === 0" class="text-muted">
      No capsules were published on this day.
    </div>

    <div 
      v-for="capsule in capsules" 
      :key="capsule.id" 
      class="card mb-3 p-3 shadow-sm"
    >
      <!-- Shared users -->
      <template v-if="capsule.users && capsule.users.length">
        <div class="d-flex gap-1">
        <span class="mx-2">• Shared with:</span>
        <div class="d-flex align-items-center gap-2 flex-wrap">
          <div 
            v-for="user in capsule.users" 
            :key="user.id" 
            class="d-flex align-items-center gap-1"
          >
            <img
              v-if="user.avatar"
              :src="`/storage/${user.avatar}`"
              alt="User avatar"
              class="rounded-circle"
              width="30"
              height="30"
              style="object-fit: cover;"
            />
            <span>{{ user.name }}</span>
          </div>
        </div>
        </div>
      </template>

      <template v-else>
        <span class="text-muted">• Not shared</span>
      </template>

      <!-- Capsule content -->
      <h5 class="fw-bold mt-3">{{ capsule.description }}</h5>
      <p class="small text-muted">
        Published at: {{ formatDateTime(capsule.created_at) }}
      </p>

      <!-- Capsule images -->
      <div 
        v-if="capsule.images && capsule.images.length" 
        class="d-flex gap-2 flex-wrap"
      >
        <img
          v-for="img in capsule.images"
          :key="img.id"
          :src="`/storage/${img.image_path}`"
          width="120"
          height="120"
          class="rounded"
          style="object-fit: cover;"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  capsules: Array,
  date: String
})

// Format for title (e.g. "October 26, 2025")
const formattedDate = computed(() => {
  return new Date(props.date).toLocaleDateString(undefined, {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  })
})

// Format for capsule timestamps (e.g. "Sunday, October 26, 2025, 3:42 PM")
const formatDateTime = (dateTime) => {
  return new Date(dateTime).toLocaleString(undefined, {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: 'numeric',
    minute: '2-digit',
  })
}
</script>

