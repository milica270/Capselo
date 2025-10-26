<template>
  <Head>
    <title>Feed</title>
  </Head>

  <div class="container-fluid bg-light py-4 min-vh-100">
    <div class="row mt-2">
      <div class="col-md-1"></div>

      <div class="col-md-4 mb-4">

        <div class="position-sticky" style="top: 6.5rem;">

      

      <!-- 🗓️ CALENDAR SECTION -->
      
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white" >
          <div class="d-flex justify-content-between align-items-center mb-4">
  <button class="btn btn-outline-success btn-sm fw-bold" @click="previousMonth">
    <i class="bi bi-chevron-left"></i>
  </button>

  <h3 class="fw-bold mb-0 text-success text-center">
    {{ currentMonthName }} {{ currentYear }}
  </h3>

  <button class="btn btn-outline-success btn-sm fw-bold" @click="nextMonth">
    <i class="bi bi-chevron-right"></i>
  </button>
</div>


          <!-- Weekday headers -->
          <div
            class="d-grid text-center fw-semibold mb-2 text-success small"
            :style="{ gridTemplateColumns: 'repeat(7, 1fr)' }"
          >
            <div v-for="day in weekDays" :key="day">{{ day }}</div>
          </div>

          <!-- Days grid -->
          <div class="d-grid calendar-grid">
            <div
  v-for="(day, index) in daysInMonth"
  :key="index"
  class="calendar-day d-flex justify-content-center align-items-center position-relative"
  :class="{
    empty: !day,
    today: isToday(day),
    published: isPublished(day),
    unpublished: isUnpublished(day)
  }"
>
  <span v-if="day" :class="{'today-published-number': isTodayPublished(day)}">{{ day }}</span>

  <div
    v-if="isToday(day)"
    class="today-dot bg-warning rounded-circle position-absolute"
  ></div>
</div>

          </div>
        </div>


        <div class="d-flex align-items-center gap-2 mt-3 w-100  filter-bar">
          <!-- Visibility Filter -->
          <select
            v-model="selectedVisibility"
            class="form-select form-select-sm fw-bold"
            style="max-width: 180px; cursor:pointer"
          >
            <option value="all">All Capsules</option>
            <option value="me">Private Capsules</option>
            <option value="friends">Friends Capsules</option>
            <option value="everyone">Public Capsules</option>
            <option value="premium">Premium Capsules</option>
          </select>

          <!-- Hashtag Search -->
          <div class="input-group input-group-sm" style="max-width: 250px;">
            <span class="input-group-text bg-white text-muted">
              <i class="bi bi-hash"></i>
            </span>
            <input
              type="text"
              v-model="searchQuery"
              class="form-control"
              placeholder="Search by hashtag..."
            />
          </div>

          <!-- Reset Filters Button -->
          <button
            class="btn btn-outline-success btn-sm fw-bold"
            @click="resetFilters"
          >
            <i class="bi bi-arrow-repeat"></i>
          </button>
        </div>

        </div>
      </div>

      <!-- 📰 FEED SECTION -->
      <div class="col-md-6">
        <!-- 🔹 FILTER BAR -->
        

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
              <template
                v-for="(participant, index) in [capsule.owner, ...capsule.users]"
                :key="participant.id"
              >
                <div
                  class="d-flex align-items-center gap-1"
                  :class="{ owner: index === 0, friend: index > 0 }"
                >
                  <img
                    class="rounded-5"
                    :src="
                      participant.avatar
                        ? `/storage/${participant.avatar}`
                        : '/images/default-avatar.png'
                    "
                    alt="avatar"
                    :class="{
                      'owner-avatar': index === 0,
                      'friend-avatar': index > 0
                    }"
                  />
                  <span
                    :class="{
                      'owner-name': index === 0,
                      'friend-name': index > 0
                    }"
                  >
                    {{ participant.name }}
                  </span>
                  <span
                    v-if="index < [capsule.owner, ...capsule.users].length - 1"
                    class="text-muted"
                    >,</span
                  >
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
              @mouseover="e => (e.target.style.transform = 'scale(1.04)')"
              @mouseleave="e => (e.target.style.transform = 'scale(1)')"
            />
          </div>

          <!-- Show full capsule button -->
          <div class="mb-2">
            <button
              class="btn btn-outline-dark fw-bold btn-sm"
              @click="openModal(capsule)"
            >
              View Full Capsule
            </button>
          </div>

          <!-- Created date -->
          <div class="d-flex justify-content-end me-3">
            <p class="text-muted small mb-0">
              <i class="bi bi-clock-fill"></i>
              {{
                new Date(capsule.created_at).toLocaleString('en-US', {
                  dateStyle: 'medium',
                  timeStyle: 'short'
                })
              }}
            </p>
          </div>
        </div>
      </div>

      <div class="col-md-1"></div>
    </div>

    <!-- 🟢 MODAL -->
    <div v-if="modalCapsule" class="modal-backdrop" @click.self="closeModal">
      <div
        class="modal-content p-4 bg-white rounded-3 shadow-lg border border-success"
      >
        <div class="d-flex justify-content-between">
          <h4 class="fw-bold mb-3 capsule-title">
            {{ modalCapsule.owner.name }}'s Capsule
            <span class="dot">.</span>
          </h4>

          <!-- Close button -->
          <button class="btn btn-danger btn-sm" @click="closeModal">X</button>
        </div>

        <!-- Participants -->
        <div
          class="d-flex align-items-center flex-wrap gap-3 mb-3 justify-content-start"
        >
          <template
            v-for="(participant, index) in [modalCapsule.owner, ...modalCapsule.users]"
            :key="participant.id"
          >
            <div
              class="d-flex align-items-center gap-2"
              :class="{ owner: index === 0, friend: index > 0 }"
            >
              <img
                class="rounded-5"
                :src="
                  participant.avatar
                    ? `/storage/${participant.avatar}`
                    : '/images/default-avatar.png'
                "
                alt="avatar"
                :class="{
                  'owner-avatar': index === 0,
                  'friend-avatar': index > 0
                }"
              />
              <span
                :class="{
                  'owner-name': index === 0,
                  'friend-name': index > 0
                }"
              >
                {{ participant.name }}
              </span>
              <span
                v-if="index < [modalCapsule.owner, ...modalCapsule.users].length - 1"
                class="text-muted"
                >,</span
              >
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
          <p class="text-muted small mb-0">
            <i class="bi bi-clock-fill"></i>
            {{
              new Date(modalCapsule.created_at).toLocaleString('en-US', {
                dateStyle: 'medium',
                timeStyle: 'short'
              })
            }}
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

// 🧩 Props
const props = defineProps({
  user: Object,
  friends: Array,
  capsules: Array
})

// 📅 Calendar Data
const dayStatus = ref({})
const weekDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
const now = new Date()

const today = now.getDate()



const month = ref(now.getMonth())
const year = ref(now.getFullYear())

const currentMonthName = computed(() =>
  new Intl.DateTimeFormat('en-US', { month: 'long' }).format(new Date(year.value, month.value))
)

const currentYear = computed(() => year.value)

const daysInMonth = computed(() => {
  const firstDay = new Date(year.value, month.value, 1).getDay() // 0 = Sunday
  const totalDays = new Date(year.value, month.value + 1, 0).getDate()

  // Add placeholders for empty days before the first real day
  const days = Array.from({ length: firstDay }, () => null)

  // Add real days (1..totalDays)
  for (let i = 1; i <= totalDays; i++) {
    days.push(i)
  }

  return days
})


// Move to previous/next month
const previousMonth = () => {
  if (month.value === 0) {
    month.value = 11
    year.value--
  } else {
    month.value--
  }
}

const nextMonth = () => {
  if (month.value === 11) {
    month.value = 0
    year.value++
  } else {
    month.value++
  }
}

const isToday = day => {
  if (!day) return false
  const current = new Date()
  return (
    day === current.getDate() &&
    month.value === current.getMonth() &&
    year.value === current.getFullYear()
  )
}

const isPublished = day => {
  if (!day) return false
  const key = `${year.value}-${month.value + 1}-${day}`
  return dayStatus.value[key] === true
}

const isUnpublished = day => {
  if (!day) return false
  const key = `${year.value}-${month.value + 1}-${day}`
  return dayStatus.value[key] === false
}


// 📱 Mobile check
const isMobile = ref(false)
const checkScreen = () => {
  isMobile.value = window.innerWidth <= 768
}

// ⚙️ Lifecycle
onMounted(async () => {
  checkScreen()
  window.addEventListener('resize', checkScreen)

  try {
    const response = await axios.get('/calendar-status', {
  params: {
    month: month.value + 1, // JS month is 0-based
    year: year.value
  }
});
dayStatus.value = response.data

  } catch (error) {
    console.error('Error loading calendar data:', error)
  }
})

onUnmounted(() => {
  window.removeEventListener('resize', checkScreen)
})

// 🔍 Filters
const selectedVisibility = ref('all')
const searchQuery = ref('')

// Filtered capsules
const filteredCapsules = computed(() => {
  return props.capsules.filter(capsule => {
    const matchesVisibility =
      selectedVisibility.value === 'all' ||
      capsule.visible_to === selectedVisibility.value

    const matchesSearch =
      !searchQuery.value ||
      capsule.description
        .toLowerCase()
        .includes(searchQuery.value.toLowerCase())

    return matchesVisibility && matchesSearch
  })
})

// Reset filters
const resetFilters = () => {
  selectedVisibility.value = 'all'
  searchQuery.value = ''
}

// 🧊 Modal logic
const modalCapsule = ref(null)
const openModal = capsule => {
  modalCapsule.value = capsule
  document.body.style.overflow = 'hidden'
}
const closeModal = () => {
  modalCapsule.value = null
  document.body.style.overflow = ''
}


const isTodayPublished = day => {
  if (!day) return false
  const current = new Date()
  const key = `${current.getFullYear()}-${current.getMonth() + 1}-${current.getDate()}`
  return day === current.getDate() && dayStatus.value[key] === true
}

</script>

<style scoped>
.cp {
  border-radius: 15%;
}

/* 👤 Owner styling */
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

/* 👥 Friend styling */
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

/* 🖼️ Images hover */
.feed-image:hover {
  transform: scale(1.05);
}

/* 🪟 Modal styling */
.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
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
  text-decoration-color: var(--bs-success);
  color: inherit;
}
.capsule-title .dot {
  color: var(--bs-success);
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
.calendar-day.published span,
.calendar-day.unpublished span {
  display: inline-flex;
  justify-content: center;
  align-items: center;
  width: 28px;
  height: 28px;
  border-radius: 50%;
  color: white;
  font-weight: 600;
  transition: all 0.3s ease;
}
.calendar-day.published span {
  background-color: var(--bs-primary);
  cursor: pointer;
}
.calendar-day.unpublished span {
  background-color: lightcoral;
}
.calendar-day.today span {
  background-color: #fff3cd;
  color: #212529;
}

.today-published-number {
  display: inline-flex;
  justify-content: center;
  align-items: center;
  width: 28px;
  height: 28px;
  border-radius: 50%;
  border: 2px solid var(--bs-primary);
  color: var(--bs-primary);
  font-weight: 700;
  background-color: white;
}

.filter-bar {
  background-color: rgba(255, 255, 255, 0.95); /* slightly transparent */
  backdrop-filter: blur(5px); /* adds soft blur to content underneath */
  padding: 0.5rem 1rem;
  border-radius: 0.5rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}


</style>
