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
                v-for="friend in friends.slice(0, 4)" 
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
            See friends page
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
      

<div class="col-md-7">
  <div ref="scrollContainer" class="p-3 bg-white rounded shadow-sm h-100">
    <!-- Main Title -->
    <h6 class="mb-3 text-uppercase" style="color: var(--lightGrey)">Capsules</h6>

    <!-- Mini Navbar -->
<div class="d-flex gap-3 mb-4">
  <button
    class="btn btn-sm d-flex align-items-center gap-1"
    :class="{'btn-success fw-bold': activeTab === 'new', 'btn-outline-success': activeTab !== 'new'}"
    @click="activeTab = 'new'"
  >
  <i class="bi bi-plus-circle fs-5"></i>
    <span>New Capsule</span>
  </button>
  <button
    class="btn btn-sm d-flex align-items-center gap-1"
    :class="{'btn-success fw-bold': activeTab === 'drafts', 'btn-outline-success': activeTab !== 'drafts'}"
    @click="activeTab = 'drafts'"
  >
  <i class="bi bi-file-earmark-text fs-5"></i>
  <span>Drafts</span>
  </button>
  <button
    class="btn btn-sm d-flex align-items-center gap-1"
    :class="{'btn-success fw-bold': activeTab === 'archive', 'btn-outline-success': activeTab !== 'archive'}"
    @click="activeTab = 'archive'"
  >
  <i class="bi bi-archive fs-5"></i>
  <span>Archive</span>
  </button>
</div>


    <!-- Capsule form (only show when New Capsule tab is active) -->
    <div v-if="activeTab === 'new'">
      <form @submit.prevent="createCapsule" class="d-flex flex-column gap-3">
        <!-- Description -->
        <!-- Hashtag selection -->
<div>
  <label class="form-label fw-bold">Select Hashtags</label>

  <div class="d-flex flex-wrap gap-2 mb-2">
    <div v-for="tag in availableHashtags" :key="tag" class="badge bg-light text-dark border" style="cursor: pointer;"
         @click="toggleHashtag(tag)">
      <span :class="{'fw-bold text-success': capsule.hashtags.includes(tag)}">{{ tag }}</span>
    </div>
  </div>

  <div class="d-flex flex-wrap gap-2 mb-2">
    <button type="button" class="btn btn-outline-primary btn-sm fw-bold" @click="addCustomHashtag">+ Add</button>
    <button type="button" class="btn btn-outline-danger btn-sm fw-bold" @click="removeLastHashtag" :disabled="!capsule.hashtags.length">− Remove</button>
  </div>

  <div v-if="capsule.hashtags.length">
    <small class="text-muted">Selected: {{ capsule.hashtags.join(' ') }}</small>
  </div>
</div>


        <!-- Invite friends -->
        <div>
          <button type="button" class="btn btn-light btn-sm fw-bold" @click="showModal = true">
            Invite Friends ({{ capsule.invited.length }})
          </button>
        </div>



        <!-- Upload images -->
        <div>
          <label class="form-label fw-bold">Add images (Add as many as you wish)</label>
          <input type="file" class="form-control" multiple @change="handleImageUpload" />
        </div>

        <div>
  <label class="form-label fw-bold">Visible to</label>
  <select class="form-select" v-model="capsule.visibleTo">
    <option value="me">Only me</option>
    <option value="friends">Friends</option>
    <option value="everyone">Everyone</option>
  </select>
</div>

        <!-- Publish switch -->
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" id="publishSwitch" v-model="capsule.published" />
          <label class="form-check-label" for="publishSwitch">Ready to publish</label>
          
        </div>

        <div class="small text-muted fst-italic ms-1">
  <i class="bi bi-info-circle me-1 text-secondary"></i>
  Your capsule will be published automatically once <strong>all invited users mark themselves as ready</strong>.
</div>
        <!-- Submit -->
        <button type="submit" class="btn btn-dark fw-bold">
          Create Capsule
        </button>
      </form>
    </div>

    
<!-- Drafts Tab -->
<div v-if="activeTab === 'drafts'" class="d-flex flex-column gap-3">
  <!-- Owned Capsules -->
  <div v-if="ownedCapsules.length">
    <h6 class="fw-bold mb-2" style="color: black">Your Drafts</h6>
    <div
      v-for="capsule in ownedCapsules"
      :key="'owned-' + capsule.id"
      class="p-3 border rounded position-relative bg-light"
    >
      <button
        class="btn btn-sm btn-outline-danger position-absolute top-0 end-0 m-2"
        @click="deleteOwnedCapsule(capsule.id)"
      >
        <i class="bi bi-x-lg"></i>
      </button>

      <p class="fw-bold mb-2">{{ capsule.description }}</p>

      <!-- Capsule Images -->
      <div class="d-flex flex-wrap gap-2 mb-2">
        <div
          v-for="image in capsule.images"
          :key="image.id"
          class="position-relative"
        >
          <img
            :src="`/storage/${image.image_path}`"
            class="rounded"
            width="100"
            height="100"
          />
          <button
            class="btn btn-sm btn-danger position-absolute top-0 end-0 p-0 px-1"
            @click="deleteImage(image.id)"
          >
            −
          </button>
        </div>

        <!-- Add image -->
        <div>
          <label class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
            +
            <input type="file" class="d-none" @change="(e) => addImage(e, capsule.id)" />
          </label>
        </div>
      </div>

      <small class="text-muted fst-italic">
        {{ capsule.users.length }} invited friends • Visibility: {{ capsule.visible_to }}
      </small>
    </div>
  </div>

  <!-- Invited Capsules -->
  <div v-if="invitedCapsules.length" class="mt-4">
    <h6 class="fw-bold mb-2" style="color: black">Capsules You’re Invited To</h6>
    <div
      v-for="capsule in invitedCapsules"
      :key="'invited-' + capsule.id"
      class="p-3 border rounded position-relative bg-light"
    >
      <button
        class="btn btn-sm btn-outline-danger position-absolute top-0 end-0 m-2"
        @click="leaveCapsule(capsule.id)"
      >
        <i class="bi bi-box-arrow-right"></i>
      </button>

      <p class="fw-bold mb-2">{{ capsule.description }}</p>

      <!-- Capsule Images -->
      <div class="d-flex flex-wrap gap-2 mb-2">
        <div
          v-for="image in capsule.images"
          :key="image.id"
          class="position-relative"
        >
          <img
            :src="`/storage/${image.image_path}`"
            class="rounded"
            width="100"
            height="100"
          />
          <button
            v-if="image.user_id === user.id"
            class="btn btn-sm btn-danger position-absolute top-0 end-0 p-0 px-1"
            @click="deleteImage(image.id)"
          >
            −
          </button>
        </div>

        <!-- Add image -->
        <div>
          <label class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
            +
            <input type="file" class="d-none" @change="(e) => addImage(e, capsule.id)" />
          </label>
        </div>
      </div>

      <small class="text-muted fst-italic">
        Owner: {{ capsule.owner.name }} • Visibility: {{ capsule.visible_to }}
      </small>
    </div>
  </div>

  <!-- If no drafts -->
  <div v-if="!ownedCapsules.length && !invitedCapsules.length" class="text-muted">
    You don’t have any draft capsules yet.
  </div>
</div>














    <div v-else>
      <p class="text-muted">Content for {{ activeTab }} will appear here.</p>
      
      
    </div>
  </div>
</div>









      
      <div class="col-md-1"></div>
    </div>
  </div>

  <!-- Invite Friends Modal -->
<div
  v-if="showModal"
  class="modal-backdrop d-flex align-items-center justify-content-center"
  @click.self="showModal = false"
>
  <div class="modal-content p-4 rounded-3 shadow" style="width: 400px; max-height: 500px; overflow-y: auto; background: white;">
    <h5 class="fw-bold mb-3">Invite Friends</h5>

    <div v-if="friends.length">
      <div v-for="friend in friends" :key="friend.id" class="form-check mb-2">
        <input
          class="form-check-input"
          type="checkbox"
          :id="'modal-friend-' + friend.id"
          :value="friend.id"
          v-model="capsule.invited"
        />
        <label class="form-check-label" :for="'modal-friend-' + friend.id">
          {{ friend.name }}
        </label>
      </div>
    </div>
    <div v-else class="text-muted">No friends available.</div>

    <div class="d-flex justify-content-end gap-2 mt-3">
      <button class="btn btn-outline-secondary btn-sm" @click="showModal = false">Cancel</button>
      <button class="btn btn-success btn-sm fw-bold" @click="showModal = false">Done</button>
    </div>
  </div>
</div>





</template>

<script setup>
import FriendCard from '../Components/FriendCard.vue'
import { ref, computed, watch, nextTick } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import hashtagsData from '../data/hashtags.json'
import { showToast } from '../utils/toast.js'


const availableHashtags = ref(hashtagsData)

const capsule = ref({
  hashtags: [],
  invited: [],
  images: [],
  visibleTo: 'me',
  published: false,
})

// toggle selection
const toggleHashtag = (tag) => {
  const index = capsule.value.hashtags.indexOf(tag)
  if (index === -1) {
    capsule.value.hashtags.push(tag)
  } else {
    capsule.value.hashtags.splice(index, 1)
  }
}

// add custom hashtag manually
const addCustomHashtag = () => {
  const newTag = prompt('Enter a new hashtag (without #):')
  if (newTag && newTag.trim()) {
    const formatted = newTag.startsWith('#') ? newTag : `#${newTag.trim()}`
    if (!availableHashtags.value.includes(formatted)) {
      availableHashtags.value.push(formatted)
    }
    if (!capsule.value.hashtags.includes(formatted)) {
      capsule.value.hashtags.push(formatted)
    }
  }
}

// remove last selected hashtag
const removeLastHashtag = () => {
  capsule.value.hashtags.pop()
}

const showModal = ref(false)
const activeTab = ref('new')
const scrollContainer = ref(null)
let lastScrollTop = 0

watch(activeTab, async (newTab, oldTab) => {
  if (scrollContainer.value) {
    // Save current scroll position before tab change
    lastScrollTop = scrollContainer.value.scrollTop
  }

  await nextTick()

  // Restore scroll position after DOM updates
  if (scrollContainer.value) {
    scrollContainer.value.scrollTop = lastScrollTop
  }
})





const props = defineProps({
  user: Object,
  users: Array,
  friendships: Array,
  ownedCapsules: Array,
  invitedCapsules: Array,
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






const handleImageUpload = (event) => {
  capsule.value.images = Array.from(event.target.files)
}

const createCapsule = () => {
  const formData = new FormData()
  formData.append('description', capsule.value.hashtags.join(' '))
  formData.append('visible_to', capsule.value.visibleTo)
  formData.append('published', capsule.value.published ? 1 : 0)

  capsule.value.invited.forEach((id, index) => {
    formData.append(`invited[${index}]`, id)
  })

 capsule.value.images.forEach(file => {
  formData.append('images[]', file)
})

  router.post(route('capsules.store'), formData, {
  preserveScroll: true,
    onSuccess: () => {
      capsule.value.hashtags = []
      capsule.value.invited = []
      capsule.value.images = []
      capsule.value.visibleTo = 'me'
      capsule.value.published = false

      showToast('Capsule created successfully!', 'success')
    },
    onError: (errors) => {
    console.error(errors)
    showToast('Failed to create capsule. Please try again.', 'danger')
  },
  })
}



const deleteOwnedCapsule = (id) => {
  if (!confirm('Are you sure you want to delete this capsule?')) return

  router.delete(route('capsules.destroy', id), {
    preserveScroll: true,
    onSuccess: () => showToast('Capsule deleted successfully.', 'success'),
    onError: () => showToast('Failed to delete capsule.', 'danger'),
  })
}

const leaveCapsule = (id) => {
  if (!confirm('Are you sure you want to leave this capsule?')) return

  router.delete(route('capsules.leave', id), {
    preserveScroll: true,
    onSuccess: () => showToast('You left the capsule.', 'success'),
    onError: () => showToast('Failed to leave capsule.', 'danger'),
  })
}

const addImage = (event, capsuleId) => {
  const file = event.target.files[0]
  if (!file) return

  const formData = new FormData()
  formData.append('image', file)

  router.post(route('capsules.addImage', capsuleId), formData, {
    preserveScroll: true,
    onSuccess: () => showToast('Image added successfully!', 'success'),
    onError: () => showToast('Failed to add image.', 'danger'),
  })
}

const deleteImage = (imageId) => {
  if (!confirm('Delete this image?')) return

  router.delete(route('capsules.deleteImage', imageId), {
    preserveScroll: true,
    onSuccess: () => showToast('Image deleted successfully.', 'success'),
    onError: () => showToast('Failed to delete image.', 'danger'),
  })
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


.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.5);
  z-index: 1050;
}

.modal-content {
  animation: fadeIn 0.2s ease-in-out;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>