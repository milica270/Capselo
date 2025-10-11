<template>
  <Head>
    <title>Friends</title>
  </Head>

  <div class="container-fluid bg-light py-3">
    <div class="row">
      <!-- Empty margin -->
      <div class="col-md-1"></div>

      <!-- Friends list -->
      <div class="col-md-3">
        <div class="d-flex flex-column gap-3 p-3 bg-white rounded h-100 shadow-sm">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="text-uppercase mb-0" style="color: var(--lightGrey)">Friends</h5>
            <span class="fw-bold" style="color: var(--lightGrey)">{{ friends.length }}</span>
          </div>

          <div class="friends-list flex-grow-1 overflow-auto">
            <div
              v-for="friend in friends"
              :key="friend.id"
              class="d-flex align-items-center justify-content-between mb-2"
            >
              <FriendCard :friend="friend" />
              <button
                class="btn btn-sm btn-outline-danger"
                @click="removeFriend(friend.id)"
                style="border: none;"
              >
                <i class="bi bi-trash-fill"></i>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Requests + Recommended -->
      <div class="col-md-4">
        <div class="d-flex flex-column gap-3 h-100">
          <!-- ✅ Friend requests -->
          <div class="d-flex flex-column gap-2 p-3 bg-white rounded shadow-sm flex-grow-1">
            <h6 class="text-dark mb-2">Friend Requests</h6>

            <div
              v-if="friendRequests.length"
              v-for="request in friendRequests"
              :key="request.id"
              class="d-flex align-items-center justify-content-between mb-2"
            >
              <FriendCard :friend="request.sender" />
              <div class="d-flex gap-1">
                <button
                  class="btn btn-primary btn-sm"
                  @click="acceptRequest(request.id)"
                  style="border: none"
                >
                  <i class="bi bi-check2"></i>
                </button>
                <button
                  class="btn btn-danger btn-sm"
                  @click="declineRequest(request.id)"
                  style="border: none"
                >
                  <i class="bi bi-trash-fill"></i>
                </button>
              </div>
            </div>

            <div v-else class="text-muted">No pending friend requests.</div>
          </div>

          <!-- Recommended friends -->
          <div class="d-flex flex-column gap-2 p-3 bg-white rounded shadow-sm">
            <h6 class="text-dark mb-2">Recommended</h6>

            <div
              v-for="recommend in recommended"
              :key="recommend.id"
              class="d-flex align-items-center justify-content-between mb-2"
            >
              <FriendCard :friend="recommend" />
              <button
                class="btn btn-sm"
                style="color: var(--yelloworange); background-color: var(--paleyellow)"
                @click="sendRequest(recommend.id)"
              >
                Send Request
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Search / Find Users -->
      <div class="col-md-3">
        <div class="d-flex flex-column gap-3 h-100">
          <div class="d-flex flex-column p-3 bg-white rounded shadow-sm flex-grow-1">
            <h6 class="text-dark mb-2">Search Users</h6>

            <input
              type="text"
              class="form-control mb-2"
              placeholder="Search by ID..."
              v-model="searchQuery"
            />

            <div v-if="filteredUsers.length" class="d-flex flex-column gap-2 mt-2">
              <div
                v-for="user in filteredUsers"
                :key="user.id"
                class="d-flex align-items-center justify-content-between"
              >
                <div class="d-flex flex-column">
                  <FriendCard :friend="user" />
                </div>

                <template v-if="user.id !== authUserId">
                  <button
                    v-if="isFriend(user.id)"
                    class="btn btn-sm"
                    type="button"
                    disabled
                    style="color: gray; background-color: #f0f0f0;"
                  >
                    Friends
                  </button>

                  <button
                    v-else-if="hasPendingRequest(user.id)"
                    class="btn btn-sm"
                    type="button"
                    disabled
                    style="color: gray; background-color: #f8f8f8;"
                  >
                    Request Sent
                  </button>

                  <button
                    v-else
                    class="btn btn-sm"
                    type="button"
                    style="color: var(--yelloworange); background-color: var(--paleyellow);"
                    @click="sendRequest(user.id)"
                  >
                    Send Request
                  </button>
                </template>
              </div>
            </div>

            <div v-else class="text-muted mt-2">No users found.</div>
          </div>

          <div class="d-flex flex-column p-3 bg-white rounded shadow-sm flex-grow-1">Ad</div>
        </div>
      </div>

      <!-- Empty margin -->
      <div class="col-md-1"></div>
    </div>
  </div>
</template>

<script setup>
import FriendCard from '../Components/FriendCard.vue'
import { ref, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'

const props = defineProps({
  users: Array,
  friendships: Array,
})

const searchQuery = ref('')
const authUserId = usePage().props.auth.user.id

// ✅ Friend requests
const friendRequests = computed(() => {
  return props.friendships
    .filter(f => f.friend_id === authUserId && f.accepted === 0)
    .map(f => {
      const sender = props.users.find(u => u.id === f.user_id)
      return { ...f, sender }
    })
})

// ✅ Search logic
const filteredUsers = computed(() => {
  if (!searchQuery.value) return []
  const query = searchQuery.value.trim()
  const user = props.users.find(u => u.id.toString() === query)
  return user ? [user] : []
})

// ✅ Pending check
const hasPendingRequest = (friendId) => {
  return props.friendships.some(f =>
    f.accepted === 0 &&
    ((f.user_id === authUserId && f.friend_id === friendId) ||
      (f.friend_id === authUserId && f.user_id === friendId))
  )
}

// ✅ Friends check
const isFriend = (friendId) => {
  return props.friendships.some(f =>
    f.accepted === 1 &&
    ((f.user_id === authUserId && f.friend_id === friendId) ||
      (f.friend_id === authUserId && f.user_id === friendId))
  )
}

// ✅ Send request
const sendRequest = (friendId) => {
  router.post(route('friend.send', friendId), {}, {
    onSuccess: () => {
      props.friendships.push({
        user_id: authUserId,
        friend_id: friendId,
        accepted: 0,
      })
    },
  })
}

// ✅ Accept request
const acceptRequest = (friendshipId) => {
  router.post(route('friend.accept', friendshipId), {}, {
    onSuccess: () => {
      const f = props.friendships.find(fr => fr.id === friendshipId)
      if (f) f.accepted = 1
    },
  })
}

// ✅ Decline request
const declineRequest = (friendshipId) => {
  router.delete(route('friend.decline', friendshipId), {
    onSuccess: () => {
      const index = props.friendships.findIndex(fr => fr.id === friendshipId)
      if (index !== -1) props.friendships.splice(index, 1)
    },
  })
}

// ✅ List of friends
const friends = computed(() => {
  return props.friendships
    .filter(f => f.accepted === 1 && (f.user_id === authUserId || f.friend_id === authUserId))
    .map(f => {
      const friendId = f.user_id === authUserId ? f.friend_id : f.user_id
      return props.users.find(u => u.id === friendId)
    })
    .filter(Boolean)
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

// ✅ Recommended friends
const recommended = computed(() => {
  const friendsIds = friends.value.map(f => f.id)
  const friendsOfFriendsIds = new Set()

  // Iterate over all friendships to find friends of friends
  props.friendships.forEach(f => {
    if (f.accepted !== 1) return
    let friendId = null
    if (friendsIds.includes(f.user_id)) friendId = f.friend_id
    else if (friendsIds.includes(f.friend_id)) friendId = f.user_id

    if (friendId && friendId !== authUserId && !friendsIds.includes(friendId)) {
      friendsOfFriendsIds.add(friendId)
    }
  })

  // Map ids to user objects
  let recommendedUsers = Array.from(friendsOfFriendsIds)
    .map(id => props.users.find(u => u.id === id))
    .filter(Boolean)

  // Take first 3
  if (recommendedUsers.length > 0) return recommendedUsers.slice(0, 3)

  // Fallback: pick 3 random users
  const potentialUsers = props.users.filter(
    u => u.id !== authUserId && !friendsIds.includes(u.id)
  )
  return shuffleArray(potentialUsers).slice(0, 3)
})

// ✅ Helper to shuffle array
const shuffleArray = (array) => {
  return array
    .map(value => ({ value, sort: Math.random() }))
    .sort((a, b) => a.sort - b.sort)
    .map(({ value }) => value)
}
</script>

<style scoped>
.friends-list {
  display: flex;
  flex-direction: column;
  gap: 4px;
  max-height: 400px;
  overflow-y: auto;
}

.btn[disabled] {
  cursor: not-allowed;
  opacity: 0.8;
}
</style>



