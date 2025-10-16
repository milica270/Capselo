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
        <div class="d-flex flex-column p-3 bg-white rounded shadow-sm">
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
    <button type="button" class="btn btn-outline-primary btn-sm fw-bold" @click="addCustomHashtag">+ Custom</button>
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
  <i class="bi bi-info-circle me-1 text-dark"></i>
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
      class="p-3 border rounded position-relative bg-light mb-3"
    >
      <button
        class="btn btn-sm btn-outline-danger position-absolute top-0 end-0 m-2"
        @click="deleteOwnedCapsule(capsule.id)"
      >
        <i class="bi bi-x-lg"></i>
      </button>

      <div v-if="editingCapsuleId === capsule.id">
  <!-- Editing mode -->
  <label class="form-label fw-bold">Edit Hashtags</label>

  <div class="d-flex flex-wrap gap-2 mb-2">
    <div
      v-for="tag in availableHashtags"
      :key="tag"
      class="badge bg-light text-dark border"
      style="cursor: pointer;"
      @click="toggleEditingHashtag(tag)"
    >
      <span :class="{'fw-bold text-success': editingHashtags.includes(tag)}">{{ tag }}</span>
    </div>
  </div>

  <div class="d-flex flex-wrap gap-2 mb-2">
    <button type="button" class="btn btn-outline-primary btn-sm fw-bold" @click="addCustomEditingHashtag">+ Add</button>
    <button type="button" class="btn btn-outline-danger btn-sm fw-bold" @click="removeLastEditingHashtag" :disabled="!editingHashtags.length">− Remove</button>
  <button class="btn btn-outline-success btn-sm fw-bold" @click="saveHashtags(capsule.id)">Save</button>
    <button class="btn btn-outline-secondary btn-sm fw-bold" @click="cancelEditingHashtags">Cancel</button>
  </div>

  <hr>
</div>

<div v-else>
  <!-- Normal display -->
  <p class="fw-bold mb-0">{{ capsule.description }}</p>
  <button
    v-if="capsule.owner_id === user.id"
    class="btn btn-sm btn-outline-secondary fw-bold mb-1"
    @click="startEditingHashtags(capsule)"
  >
    Edit Hashtags
  </button>
  <hr>
</div>


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

      <hr>
      <!-- Ready status table -->
<div class="mt-3">

  <div class="d-flex justify-content-between align-items-center">
  <h6 class="fw-bold mb-2">Ready to publish?</h6>

  <div class="form-check form-switch">
  <input
    class="form-check-input"
    type="checkbox"
    :id="'readySwitch-' + capsule.id"
    :checked="isUserReady(capsule)"
    @change="toggleReady(capsule)"
  />
  <label class="form-check-label fw-bold" :for="'readySwitch-' + capsule.id">
    I'm Ready to Publish
  </label>
</div>
</div>

  

  <ul class="list-unstyled mb-0">
    <!-- Owner -->
    <li class="d-flex align-items-center gap-2">
      <i
        class="bi"
        :class="capsule.ready ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger'"
      ></i>
      <span class="fw-bold">{{ user.name }} (You)</span>
    </li>

    <!-- Invited friends -->
    <li
      v-for="friend in capsule.users"
      :key="'friend-' + friend.id"
      class="d-flex align-items-center gap-2"
    >
      <i
        class="bi"
        :class="friend.pivot.ready ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger'"
      ></i>
      <span>{{ friend.name }}</span>
    </li>
  </ul>
</div>

<div class="small text-muted fst-italic ms-1 mt-2">
  <i class="bi bi-info-circle me-1 text-dark"></i>
  Your capsule will be published automatically once <strong>all invited users mark themselves as ready</strong>.
</div>

<!-- Invite more friends -->
<div class="mt-3">
  <button
    class="btn btn-outline-secondary btn-sm fw-bold"
    @click="openInviteModal(capsule)"
  >
    <i class="bi bi-person-plus-fill me-1"></i>
    Invite More Friends
  </button>
</div>



<div class="mt-2">
  <label class="form-label fw-bold">Visible to</label>
  <select
    class="form-select"
    v-model="capsule.visible_to"
    @change="updateVisibility(capsule)"
    :disabled="capsule.owner_id !== user.id"
  >
    <option value="me">Only me</option>
    <option value="friends">Friends</option>
    <option value="everyone">Everyone</option>
  </select>
</div>

    </div>
  </div>

  <!-- Invited Capsules -->
  <div v-if="invitedCapsules.length" class="mt-4">
    <h6 class="fw-bold mb-2" style="color: black">Capsules You’re Invited To</h6>
    <div
      v-for="capsule in invitedCapsules"
      :key="'invited-' + capsule.id"
      class="p-3 border rounded position-relative bg-light mb-3"
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


<!-- Ready status + invited friends -->
<div class="mt-3">
  <div class="d-flex justify-content-between align-items-center">
    <h6 class="fw-bold mb-2">Ready to publish?</h6>

    <!-- ✅ Switch for invited user -->
    <div class="form-check form-switch">
      <input
        class="form-check-input"
        type="checkbox"
        :id="'readySwitchInvited-' + capsule.id"
        :checked="isUserReady(capsule)"
        @change="toggleReady(capsule)"
      />
      <label class="form-check-label fw-bold" :for="'readySwitchInvited-' + capsule.id">
        I'm Ready to Publish
      </label>
    </div>
  </div>

  <!-- ✅ List of all invited friends -->
  <ul class="list-unstyled mb-0">
    <!-- Owner -->
    <li class="d-flex align-items-center gap-2">
      <i
        class="bi"
        :class="capsule.ready ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger'"
      ></i>
      <span class="fw-bold">{{ capsule.owner.name }} (Owner)</span>
    </li>

    <!-- Invited users -->
    <li
      v-for="friend in capsule.users"
      :key="'invited-friend-' + friend.id"
      class="d-flex align-items-center gap-2"
    >
      <i
        class="bi"
        :class="friend.pivot.ready ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger'"
      ></i>
      <span>{{ friend.name }}</span>
    </li>
  </ul>
</div>

<div class="small text-muted fst-italic ms-1 mt-2">
  <i class="bi bi-info-circle me-1 text-dark"></i>
  The capsule will be published automatically once <strong>all invited users mark themselves as ready</strong>.
</div>



      <div class="text-dark fst-italic fw-bold mt-2">
        Owner: {{ capsule.owner.name }} • Visibility: {{ capsule.visible_to }}
      </div>
    </div>
  </div>

  <!-- If no drafts -->
  <div v-if="!ownedCapsules.length && !invitedCapsules.length" class="text-muted">
    You don’t have any draft capsules yet.
  </div>
</div>














<!-- Archive Tab -->
<div v-if="activeTab === 'archive'" class="d-flex flex-column gap-3">
  <h6 class="fw-bold mb-3" style="color: black">Archive</h6>

  <!-- Combine owned and invited ready capsules -->
  <div
    v-for="capsule in [...ownedReadyCapsules, ...invitedReadyCapsules]"
    :key="'archive-' + capsule.id"
    class="p-3 border rounded position-relative bg-light mb-3 shadow-sm"
  >
    <!-- Top labels -->
    <div class="d-flex justify-content-between align-items-center mb-2">
      <!-- Owner / Invited -->
      <span
        class="fw-bold px-2 py-1 rounded"
        style="color: var(--darkGrey)"
      >
        {{ capsule.owner_id === user.id ? 'OWNER' : 'INVITED' }}
      </span>

      <div class="d-flex gap-2">
       <!-- Created date -->
    <p class="text-muted small mb-2">{{ new Date(capsule.created_at).toLocaleString('en-US', {
        dateStyle: 'medium',
        timeStyle: 'short'
      }) }}
    </p>


      <!-- Visibility -->
      <span
        class="fw-bold px-2 py-1 rounded text-white"
        :class="{
          'bg-danger': capsule.visible_to === 'me',
          'bg-info': capsule.visible_to === 'friends',
          'bg-warning text-dark': capsule.visible_to === 'everyone'
        }"
      >
        {{
          capsule.visible_to === 'me'
            ? 'Only Me'
            : capsule.visible_to === 'friends'
            ? 'Friends'
            : 'Everyone'
        }}
      </span>
    </div>
    </div>

   

    <!-- Description -->
    <p class="mb-2 fw-bold" style="white-space: pre-wrap;">
      {{ capsule.description }}
    </p>

    <!-- Images -->
    <div
      v-if="capsule.images && capsule.images.length"
      class="d-flex flex-wrap gap-2 mb-2"
    >
      <img
        v-for="image in capsule.images"
        :key="'img-' + image.id"
        :src="`/storage/${image.image_path}`"
        class="rounded"
        width="100"
        height="100"
        style="object-fit: cover;"
      />
    </div>
    <div v-else class="text-muted fst-italic mb-2">
      No images available.
    </div>

    <!-- Shared with section -->
    <div>
      <strong>Shared with: </strong>
      <span v-if="capsule.owner_id === user.id">
        <!-- Owner view: list invited friends -->
        <template v-if="capsule.users && capsule.users.length">
          <span
            v-for="(invited, idx) in capsule.users"
            :key="'invited-' + invited.id"
          >
            {{ invited.name }}<span v-if="idx !== capsule.users.length - 1">, </span>
          </span>
        </template>
        <span v-else class="text-muted fst-italic">No invited users</span>
      </span>

      <span v-else>
        <!-- Invited view: show owner + other invited users -->
        <span class="fw-bold">{{ capsule.owner?.name }}</span>
        <small class="text-muted">(owner)</small>
        <span v-if="capsule.users && capsule.users.length">
          , 
          <span
            v-for="(friend, idx) in capsule.users.filter(u => u.id !== user.id)"
            :key="'friend-' + friend.id"
          >
            {{ friend.name }}<span v-if="idx !== capsule.users.filter(u => u.id !== user.id).length - 1">, </span>
          </span>
        </span>
      </span>
    </div>
  </div>

  <!-- No capsules message -->
  <div
    v-if="[...ownedReadyCapsules, ...invitedReadyCapsules].length === 0"
    class="text-muted text-center"
  >
    No archived capsules yet.
  </div>
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




<!-- Invite More Friends (for existing capsule) -->
<div
  v-if="showInviteMoreModal"
  class="modal-backdrop d-flex align-items-center justify-content-center"
  @click.self="closeInviteModal"
>
  <div
    class="modal-content p-4 rounded-3 shadow"
    style="width: 400px; max-height: 500px; overflow-y: auto; background: white;"
  >
    <h5 class="fw-bold mb-3">Invite More Friends</h5>

    <div v-if="friends.length">
  <div
    v-for="friend in friends"
    :key="'invite-' + friend.id"
    class="form-check mb-2 d-flex justify-content-between align-items-center"
  >
    <div>
      <input
        class="form-check-input me-2"
        type="checkbox"
        :id="'invite-more-' + friend.id"
        :value="friend.id"
        v-model="selectedInvitees"
        :disabled="currentCapsule?.users.some(u => u.id === friend.id)"
      />
      <label class="form-check-label" :for="'invite-more-' + friend.id">
        {{ friend.name }}
        <small
          v-if="currentCapsule?.users.some(u => u.id === friend.id)"
          class="text-muted"
        >
          (Already invited)
        </small>
      </label>
    </div>

    <!-- ❌ Button aligned to the right -->
    <button
      v-if="currentCapsule?.users.some(u => u.id === friend.id)"
      class="btn btn-sm btn-outline-danger ms-3"
      @click="removeInvitation(friend.id)"
      title="Remove invitation"
    >
      ✕
    </button>
  </div>
</div>

    <div v-else class="text-muted">No friends available.</div>

    <div class="d-flex justify-content-end gap-2 mt-3">
      <button class="btn btn-outline-secondary btn-sm" @click="closeInviteModal">Cancel</button>
      <button class="btn btn-success btn-sm fw-bold" @click="inviteMoreFriends">
        Add Selected
      </button>
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
  ownedReadyCapsules: Array,
  invitedReadyCapsules: Array,
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




const editingCapsuleId = ref(null)
const editingHashtags = ref([])

const startEditingHashtags = (capsule) => {
  editingCapsuleId.value = capsule.id
  editingHashtags.value = [...(capsule.description?.split(' ') || [])]
}

const cancelEditingHashtags = () => {
  editingCapsuleId.value = null
  editingHashtags.value = []
}

const saveHashtags = (capsuleId) => {
  router.put(route('capsules.updateHashtags', capsuleId), {
    hashtags: editingHashtags.value,
  }, {
    preserveScroll: true,
    onSuccess: () => {
      showToast('Hashtags updated successfully!', 'success')
      editingCapsuleId.value = null
    },
    onError: () => showToast('Failed to update hashtags.', 'danger'),
  })
}

// Reuse your toggle/add/remove logic for hashtag editing
const toggleEditingHashtag = (tag) => {
  const index = editingHashtags.value.indexOf(tag)
  if (index === -1) editingHashtags.value.push(tag)
  else editingHashtags.value.splice(index, 1)
}

const addCustomEditingHashtag = () => {
  const newTag = prompt('Enter a new hashtag (without #):')
  if (newTag && newTag.trim()) {
    const formatted = newTag.startsWith('#') ? newTag : `#${newTag.trim()}`
    if (!availableHashtags.value.includes(formatted)) {
      availableHashtags.value.push(formatted)
    }
    if (!editingHashtags.value.includes(formatted)) {
      editingHashtags.value.push(formatted)
    }
  }
}

const removeLastEditingHashtag = () => {
  editingHashtags.value.pop()
}


function isUserReady(capsule) {
  // Owner readiness comes from capsule.ready
  if (capsule.owner_id === props.user.id) {
    return capsule.ready === 1
  }

  // For invited users: find pivot.ready
  const friend = capsule.users.find(u => u.id === props.user.id)
  return friend ? friend.pivot.ready === 1 : false
}

// Toggle readiness
function toggleReady(capsule) {
  router.post(
    route('capsules.toggleReady', capsule.id),
    {},
    {
      preserveScroll: true,
      onSuccess: () => {
        showToast('Your readiness has been updated!', 'success')
      },
      onError: () => {
        showToast('Something went wrong.', 'danger')
      },
    }
  )
}


const showInviteMoreModal = ref(false)
const selectedInvitees = ref([])
const currentCapsule = ref(null)

const openInviteModal = (capsule) => {
  currentCapsule.value = capsule
  selectedInvitees.value = []
  showInviteMoreModal.value = true
}

const closeInviteModal = () => {
  showInviteMoreModal.value = false
  currentCapsule.value = null
}


const inviteMoreFriends = () => {
  if (!currentCapsule.value || selectedInvitees.value.length === 0) {
    showToast('Select at least one friend to invite.', 'warning')
    return
  }

  router.post(
    route('capsules.inviteMore', currentCapsule.value.id),
    { invited: selectedInvitees.value },
    {
      preserveScroll: true,
      onSuccess: () => {
        showToast('Friends invited successfully!', 'success')
        closeInviteModal()
      },
      onError: () => showToast('Failed to invite friends.', 'danger'),
    }
  )
}


const removeInvitation = async (userId) => {
    if (!currentCapsule.value) return;

    router.delete(route('capsules.removeInvitation', { 
        capsule: currentCapsule.value.id, 
        user: userId 
    }), {
      preserveScroll: true,
    preserveState: true,
        onSuccess: () => {
            currentCapsule.value.users = currentCapsule.value.users.filter(u => u.id !== userId);
            showToast('Invitation removed successfully!', 'danger');
        },
        onError: () => {
            showToast('Failed to remove invitation.', 'danger');
        }
    });
};


const updateVisibility = (capsule) => {
  router.put(route('capsules.updateVisibility', capsule.id), {
    visible_to: capsule.visible_to
  }, {
    preserveScroll: true,
    onSuccess: () => showToast('Visibility updated successfully!', 'success'),
    onError: () => showToast('Failed to update visibility.', 'danger'),
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