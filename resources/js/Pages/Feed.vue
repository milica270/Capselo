<template>
  <Head>
    <title>Feed</title>
  </Head>

  <div class="container-fluid bg-light py-4 min-vh-100">
    <div class="row mt-3">
      <div class="col-md-1"></div>
        
      <div class="col-md-10">

        <!-- Empty state -->
        <div v-if="capsules.length === 0" class="text-center text-muted mt-5">
          No capsules to show yet.
        </div>

        <!-- Capsules -->
        <div
          v-for="capsule in capsules"
          :key="capsule.id"
          class="p-3 border cp position-relative bg-white mb-4 shadow-sm"
        >
          <!-- Header -->
          <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
            <!-- Participants -->
            <div class="d-flex align-items-center flex-wrap gap-2">
              <template v-for="(participant, index) in [capsule.owner, ...capsule.users]" :key="participant.id">
                <div class="d-flex align-items-center gap-1">
                  <img
                    :src="participant.avatar
                      ? `/storage/${participant.avatar}`
                      : '/images/default-avatar.png'"
                    alt="avatar"
                    class="rounded-circle border"
                    width="35"
                    height="35"
                    style="object-fit: cover;"
                  />
                  <span class="fw-semibold" style="color: var(--darkGrey);">
                    {{ participant.name }}
                  </span>
                  <span v-if="index < [capsule.owner, ...capsule.users].length - 1" class="text-muted">,</span>
                </div>
              </template>
            </div>

            
          </div>

          <!-- Description -->
          <p class="mb-2 fw-bold" style="white-space: pre-wrap;">
            {{ capsule.description }}
          </p>

          <!-- Images -->
          <div
            v-if="capsule.images && capsule.images.length"
            class="d-flex flex-wrap justify-content-start gap-2 mb-3"
          >
            <img
              v-for="image in capsule.images"
              :key="image.id"
              :src="`/storage/${image.image_path}`"
              alt="capsule image"
              class="rounded"
              width="100"
              height="100"
              style="object-fit: cover; cursor: pointer; transition: transform 0.2s;"
              @mouseover="e => e.target.style.transform = 'scale(1.04)'"
              @mouseleave="e => e.target.style.transform = 'scale(1)'"
            />
          </div>

          <!-- Created date -->
<div class="d-flex justify-content-end me-3">
  <p class="text-muted small mb-0">
    {{ new Date(capsule.created_at).toLocaleString('en-US', { dateStyle: 'medium', timeStyle: 'short' }) }}
  </p>
</div>
        </div>
      </div>

      <div class="col-md-1"></div>
    </div>
  </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'

defineProps({
  user: Object,
  friends: Array,
  capsules: Array,
})
</script>

<style scoped>
.capsule-card:hover {
  transform: translateY(-2px);
  transition: all 0.2s ease-in-out;
}

.cp {
    border-radius: 15%;
}
</style>





