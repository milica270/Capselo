<template>
  <Head>
    <title>Feed</title>
  </Head>

  <div class="container-fluid bg-light py-4 min-vh-100">
    <div class="row mt-3">
      <div class="col-md-1"></div>
      


<div class="col-md-4 mb-4">
  <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <h3 class="fw-bold mb-4 text-success text-start">
      {{ currentMonthName }} {{ currentYear }}
    </h3>

    <!-- Weekday headers -->
    <div class="d-grid text-center fw-semibold mb-2 text-success small"
         :style="{ gridTemplateColumns: 'repeat(7, 1fr)' }">
      <div v-for="day in weekDays" :key="day">{{ day }}</div>
    </div>

    <!-- Days grid -->
    <div class="d-grid calendar-grid">
<div
  v-for="day in daysInMonth"
  :key="day"
  class="calendar-day d-flex justify-content-center align-items-center position-relative"
  :class="{
    'today': day === today,
    'published': dayStatus[day] === true && day < today,
    'unpublished': dayStatus[day] === false && day < today
  }"
>
  <span>{{ day }}</span>

  <div
    v-if="day === today"
    class="today-dot bg-warning rounded-circle position-absolute"
  ></div>
</div>

    </div>
  </div>
</div>





      <div class="col-md-6">


        <!-- 🔹 FILTER BAR -->
        <div class="d-flex align-items-center gap-2 mb-4 w-100">
          <!-- Visibility Filter -->
          <select v-model="selectedVisibility" class="form-select form-select-sm fw-bold" style="max-width: 180px;">
            <option value="all">All Capsules</option>
            <option value="me">Private Capsules</option>
            <option value="friends">Friends Capsules</option>
            <option value="everyone">Public Capsules</option>
            <option value="premium">Premium Capsules</option>
          </select>

          <!-- Hashtag Search -->
          <div class="input-group input-group-sm" style="max-width: 250px;">
            <span class="input-group-text bg-white text-muted"><i class="bi bi-hash"></i></span>
            <input 
              type="text" 
              v-model="searchQuery" 
              class="form-control" 
              placeholder="Search by hashtag..."
            />
          </div>

          <!-- Reset Filters Button -->
          <button class="btn btn-outline-success btn-sm fw-bold" @click="resetFilters">
            <i class="bi bi-arrow-repeat"></i>
          </button>
        </div>

        <!-- Empty state -->
        <div v-if="capsules.length === 0" class="text-center text-muted mt-5">
          No capsules to show yet.
        </div>

        <!-- Capsules -->
        <div
          v-for="capsule in filteredCapsules"
          :key="capsule.id"
          class="p-3 border cp position-relative bg-white mb-4 shadow-sm"
        >
          <!-- Header -->
          <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
            <!-- Participants -->
            <div class="d-flex align-items-center flex-wrap gap-2">
              <template v-for="(participant, index) in [capsule.owner, ...capsule.users]" :key="participant.id">
                <div class="d-flex align-items-center gap-1" :class="{'owner': index === 0, 'friend': index > 0}">
                  <img
                  class="rounded-5"
                    :src="participant.avatar ? `/storage/${participant.avatar}` : '/images/default-avatar.png'"
                    alt="avatar"
                    :class="{'owner-avatar': index === 0, 'friend-avatar': index > 0}"
                  />
                  <span :class="{'owner-name': index === 0, 'friend-name': index > 0}">
                    {{ participant.name }}
                  </span>
                  <span v-if="index < [capsule.owner, ...capsule.users].length - 1" class="text-muted">,</span>
                </div>
              </template>
            </div>
          </div>

          <!-- Description -->
          <p class="mb-2 fw-semibold" style="white-space: pre-wrap;">
            {{ capsule.description }}
          </p>

          <!-- Images -->
          <div
            v-if="capsule.images && capsule.images.length"
            class="d-flex flex-wrap justify-content-start gap-2 mb-2"
          >
            <img
  v-for="(image, i) in capsule.images.slice(0, isMobile ? 4 : 5)"
  :key="image.id"
  :src="`/storage/${image.image_path}`"
  alt="capsule image"
  class="rounded feed-image"
  width="100"
  height="100"
  style="object-fit: cover; cursor: pointer; transition: transform 0.2s;"
  @mouseover="e => e.target.style.transform = 'scale(1.04)'"
  @mouseleave="e => e.target.style.transform = 'scale(1)'"
/>
          </div>

          <!-- Show full capsule button -->
          <div class="mb-2">
            <button class="btn btn-outline-dark fw-bold btn-sm" @click="openModal(capsule)">
              View Full Capsule
            </button>
          </div>

          <!-- Created date -->
          <div class="d-flex justify-content-end me-3">
            <p class="text-muted small mb-0"> <i class="bi bi-clock-fill"></i>
              {{ new Date(capsule.created_at).toLocaleString('en-US', { dateStyle: 'medium', timeStyle: 'short' }) }}
            </p>
          </div>
        </div>
      </div>

      <div class="col-md-1"></div>
    </div>

    <!-- Modal -->
<div v-if="modalCapsule" class="modal-backdrop" @click.self="closeModal">
  <div class="modal-content p-4 bg-white rounded-3 shadow-lg border border-success">
    <div class="d-flex justify-content-between">
    <h4 class="fw-bold mb-3 capsule-title">{{ modalCapsule.owner.name }}'s Capsule<span class="dot">.</span></h4>
    <!-- Close button -->
    <div class="d-flex justify-content-end">
      <button class="btn btn-danger btn-sm" @click="closeModal">X</button>
    </div>
</div>
    
    <!-- Participants -->
    <div class="d-flex align-items-center flex-wrap gap-3 mb-3 justify-content-start">

      <template v-for="(participant, index) in [modalCapsule.owner, ...modalCapsule.users]" :key="participant.id">
        <div class="d-flex align-items-center gap-2" :class="{'owner': index === 0, 'friend': index > 0}">
          <img
            class="rounded-5"
            :src="participant.avatar ? `/storage/${participant.avatar}` : '/images/default-avatar.png'"
            alt="avatar"
            :class="{'owner-avatar': index === 0, 'friend-avatar': index > 0}"
          />
          <span :class="{'owner-name': index === 0, 'friend-name': index > 0}">
            {{ participant.name }}
          </span>
          <span v-if="index < [modalCapsule.owner, ...modalCapsule.users].length - 1" class="text-muted">,</span>
        </div>
      </template>
    </div>

    <!-- Capsule description -->
    
    <p class="mb-3" style="font-size: 1.2rem; white-space: pre-wrap;">
      {{ modalCapsule.description }}
    </p>

    <!-- Capsule images -->
    <div class="d-flex flex-wrap gap-3 mb-3 justify-content-start">
      <img
        v-for="image in modalCapsule.images"
        :key="image.id"
        :src="`/storage/${image.image_path}`"
        class="rounded"
        width="150"
        height="150"
        style="object-fit: cover;"
      />
    </div>

    <!-- Created date -->
          <div class="d-flex justify-content-end me-3">
            <p class="text-muted small mb-0"> <i class="bi bi-clock-fill"></i>
              {{ new Date(modalCapsule.created_at).toLocaleString('en-US', { dateStyle: 'medium', timeStyle: 'short' }) }}
            </p>
          </div>

    
  </div>
</div>


  </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'
import { ref, computed, onMounted, onUnmounted } from 'vue'
import axios from 'axios'




const dayStatus = ref({}) // { 1: true, 2: false, ... }


const props = defineProps({
  user: Object,
  friends: Array,
  capsules: Array,
})

const isMobile = ref(false)

const checkScreen = () => {
  isMobile.value = window.innerWidth <= 768 // adjust breakpoint if needed
}

onMounted(async () => {
  checkScreen()
  window.addEventListener('resize', checkScreen)

  try {
    const response = await axios.get('/calendar-status')
    dayStatus.value = response.data
  } catch (error) {
    console.error('Error loading calendar data:', error)
  }
})


onUnmounted(() => {
  window.removeEventListener('resize', checkScreen)
})

const modalCapsule = ref(null)
const selectedVisibility = ref('all')
const searchQuery = ref('')
const weekDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
const now = new Date();



// 🔹 Filtered capsules logic
const filteredCapsules = computed(() => {
  return props.capsules.filter(capsule => {
    const matchesVisibility =
      selectedVisibility.value === 'all' ||
      capsule.visible_to === selectedVisibility.value

    const matchesSearch =
      !searchQuery.value ||
      capsule.description.toLowerCase().includes(searchQuery.value.toLowerCase())

    return matchesVisibility && matchesSearch
  })
})

// 🔹 Reset filters
const resetFilters = () => {
  selectedVisibility.value = 'all'
  searchQuery.value = ''
}

// 🔹 Modal open/close
const openModal = (capsule) => {
  modalCapsule.value = capsule
  document.body.style.overflow = 'hidden'
}

const closeModal = () => {
  modalCapsule.value = null
  document.body.style.overflow = ''
}


const today = new Date().getDate()
const currentMonth = new Date().getMonth()
const currentYear = new Date().getFullYear()

const currentMonthName = new Intl.DateTimeFormat('en-US', { month: 'long' }).format(
  new Date(currentYear, currentMonth)
)

// number of days in this month
const daysInMonth = Array.from(
  { length: new Date(currentYear, currentMonth + 1, 0).getDate() },
  (_, i) => i + 1
)

</script>


<style scoped>
.cp {
  border-radius: 15%;
}

/* Owner styling */
.owner-avatar {
  width: 45px;
  height: 45px;
  object-fit: cover;
}
.owner-name {
  font-weight: bold;
  font-size: 1rem;
  color: var(--bs-dark);
}

/* Friend styling */
.friend-avatar {
  width: 35px;
  height: 35px;
  object-fit: cover;
}
.friend-name {
  font-weight: 500;
  font-size: 0.9rem;
  color: var(--darkGrey);
}

/* Images hover effect */
.feed-image:hover {
  transform: scale(1.05);
}

/* Modal styling */
.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0,0,0,0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 3000;
}

.modal-content {
  max-width: 700px;
  width: 90%;
}

.capsule-title {
  text-decoration: underline;
  text-decoration-color: var(--bs-success); /* underline color */
  color: inherit;
}

.capsule-title .dot {
  color: var(--bs-success); /* make the dot green */
}


/* 🗓️ Calendar styling */
.calendar-grid {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  gap: 0.3rem;
}

.calendar-day {
  aspect-ratio: 1;
  border-radius: 8px;
  font-size: 0.8rem;
  font-weight: 500;
  color: #343a40;
  background-color: #f8f9fa;
  border: 1px solid #e9ecef;
  cursor: default;
}

.calendar-day.today {
  background-color: #fff3cd !important;
  border-color: #ffc107 !important;
  color: #212529;
  font-weight: 700;
  position: relative;
}

.today-dot {
  width: 5px;
  height: 5px;
  bottom: 5px;
  left: 50%;
  transform: translateX(-50%);
}


.calendar-day.published span {
  display: inline-flex;
  justify-content: center;
  align-items: center;
  width: 28px;
  height: 28px;
  border-radius: 50%;
  background-color: var(--bs-primary);
  color: white;
  font-weight: 600;
  transition: all 0.3s ease;
}

.calendar-day.unpublished span {
  display: inline-flex;
  justify-content: center;
  align-items: center;
  width: 28px;
  height: 28px;
  border-radius: 50%;
  background-color: lightcoral;
  color: white;
  font-weight: 600;
  transition: all 0.3s ease;
}

.calendar-day.today span {
  background-color: #fff3cd;
  color: #212529;
}



</style>
