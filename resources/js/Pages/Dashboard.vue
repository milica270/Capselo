<template>
  <Head>
    <title>Dashboard</title>
  </Head>

  <div class="container-fluid bg-light">
    <div class="row mt-3">
      <div class="col-md-1"></div>

      <!-- User profile card -->
      <div class="col-md-3">
        <div class="d-flex flex-column gap-3 p-3 bg-white rounded h-100 shadow-sm position-relative">
          <div class="position-absolute top-0 end-0 me-2 mt-1 fw-bold" style="color: var(--lightGrey)">
            ID: {{ user.id }}
          </div>

          <!-- Avatar + Name -->
          <div class="d-flex align-items-center gap-3">
            <img
              :src="user.avatar?.startsWith('http') ? user.avatar : `/storage/${user.avatar}`"
              alt="Avatar"
              class="rounded-4"
              width="90"
              height="90"
            />
            <div class="d-flex flex-column">
              <h5 class="mb-0 fw-bold">{{ user.name }} {{ user.lastName }}</h5>
              <small>{{ user.email }}</small>
            </div>
          </div>

          <!-- Edit profile button -->
          <Link
            :href="route('edit_profile')"
            class="btn btn-sm w-100 fs-6 fw-bold"
            type="button"
            style="color: var(--yelloworange); background-color: var(--paleyellow)"
          >
            Edit Profile
          </Link>

          <!-- Birthday -->
          <div class="d-flex align-items-center gap-1">
            <i class="bi bi-cake2-fill"></i>
            <span>{{ formatDate(user.birthdate) }}</span>
          </div>

          <!-- Location -->
          <div class="d-flex align-items-center gap-1">
            <i class="bi bi-geo-alt-fill"></i>
            <span>{{ user.country }}, {{ user.city }}</span>
          </div>

          <!-- Role -->
          <div class="d-flex align-items-center gap-1">
            <i class="bi bi-briefcase-fill"></i>
            <div v-if="user.role === 'school'">
              <span v-if="user.schoolType === 'elementary'">Elementary School</span>
              <span v-else-if="user.schoolType === 'highschool'">High School</span>
              <span v-else-if="user.schoolType === 'university'">University</span>
            </div>
            <div v-else>
              <span>{{ user.jobType }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-3"></div>

      <!-- Empty space -->
      <div class="col-md-7"></div>
      <div class="col-md-1"></div>
    </div>
    <div class="row mt-3">
      <div class="col-md-1"></div>
      <div class="col-md-3">
        <div class="d-flex flex-column p-3 bg-white rounded h-100 shadow-sm">
          <!-- Header -->
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0 text-uppercase" style="color: var(--lightGrey)">Friends</h6>
            <Link :href="route('friends')" class="text-decoration-none fw-bold" style="color: var(--lightGrey)">
              {{ friends.length }}
            </Link>
          </div>

          <!-- Friends list -->
          <div class="friends-list flex-grow-1 overflow-auto mb-2">
            <div v-if="friends.length">
              <div
                v-for="friend in friends.slice(0, 3)" 
                :key="friend.id"
                class="d-flex align-items-center justify-content-between mb-2"
              >
                <FriendCard :friend="friend" />
                <button
                  class="btn btn-sm btn-outline-danger"
                  @click="removeFriend(friend.id)"
                  style="border:none; padding: 2px 5px;"
                >
                  <i class="bi bi-trash-fill"></i>
                </button>
              </div>
            </div>
            <div v-else class="text-muted">No friends yet.</div>
          </div>

          <!-- See all -->
          <Link
            :href="route('friends')"
            class="mt-2 text-decoration-none"
            style="font-size: 0.9rem"
          >
            See all
          </Link>

          <!-- Requests link -->
          <div class="text-center">
            <Link
              :href="route('friends')"
              class="text-decoration-none fw-bold"
              style="color: var(--darkGrey)"
            >
              Requests: {{ friendRequests.length }}
            </Link>
          </div>

          
        </div>
      </div>
      <div class="col-md-7"></div>
      <div class="col-md-1"></div>
    </div>
  </div>
</template>

<script setup>
import FriendCard from '../Components/FriendCard.vue'
import { computed } from 'vue'
import { usePage, router } from '@inertiajs/vue3'

const props = defineProps({
  user: Object,
  users: Array,
  friendships: Array,
})

const authUserId = usePage().props.auth.user.id

// ✅ Compute user's friends
const friends = computed(() => {
  return props.friendships
    .filter(f => f.accepted === 1 && (f.user_id === authUserId || f.friend_id === authUserId))
    .map(f => {
      const friendId = f.user_id === authUserId ? f.friend_id : f.user_id
      return props.users.find(u => u.id === friendId)
    })
    .filter(Boolean)
})

// ✅ Compute friend requests
const friendRequests = computed(() => {
  return props.friendships.filter(f => f.friend_id === authUserId && f.accepted === 0)
})

// ✅ Remove a friend
const removeFriend = (friendId) => {
  const friendship = props.friendships.find(f =>
    f.accepted === 1 &&
    ((f.user_id === authUserId && f.friend_id === friendId) ||
     (f.friend_id === authUserId && f.user_id === friendId))
  )

  if (friendship) {
    router.delete(route('friend.remove', friendId), {
      onSuccess: () => {
        const index = props.friendships.indexOf(friendship)
        if (index !== -1) props.friendships.splice(index, 1)
      },
    })
  }
}

const formatDate = (dateString) => {
  if (!dateString) return '-'
  const date = new Date(dateString)
  const options = { year: 'numeric', month: 'long', day: 'numeric' }
  return new Intl.DateTimeFormat('en-US', options).format(date)
}
</script>

<style scoped>
.friends-list {
  display: flex;
  flex-direction: column;
  gap: 4px;
  max-height: 200px;
  overflow-y: auto;
}

.btn[disabled] {
  cursor: not-allowed;
  opacity: 0.8;
}
</style>