<template>
  <!-- Desktop Navbar -->
  <nav v-if="!isMobile" class="navbar navbar-light navbar-expand-lg bg-success px-4 py-2 text-white fixed-top">
    <div class="container-fluid">
      <!-- Logo -->
      <div class="d-flex align-items-center gap-4">
        <div style="background-color: white;" class="rounded-pill text-center">
          <div class="d-flex align-items-center justify-content-center">
            <img 
              src="../../../../public/storage/logo/logo.png" 
              alt="Capselo Logo" 
              width="50"
            >
            <div class="ms-0 me-3">
              <h3 class="fw-bold mb-0">
                <span style="color: black; text-decoration: underline; text-decoration-color: var(--bs-success);">
                  Capselo</span>
                <span style="color: var(--bs-success);">.</span>
              </h3>
            </div>
          </div>
        </div>
      </div>

      <!-- Collapsible content -->
      <div class="collapse navbar-collapse" id="capseloNavbar">
        <!-- LEFT ITEMS -->
        <div class="navbar-nav me-auto d-flex align-items-center gap-4 mt-2 mt-lg-0">
          <Link :href="route('feed')" class="nav-link text-white d-flex align-items-center gap-1">
            <i class="bi bi-house-door-fill fs-4"></i>
            <span class="fw-bold">Feed</span>
          </Link>

          <Link :href="route('premium')" class="nav-link text-white d-flex align-items-center gap-1">
            <i class="bi bi-gem fs-4"></i>
            <span class="fw-bold">Premium</span>
          </Link>
        </div>

        <!-- RIGHT ITEMS -->
        <div class="d-flex align-items-center gap-3 ms-auto mt-2 mt-lg-0 right-items">
          <Link :href="route('notifications')" class="text-white position-relative">
            <i class="bi bi-bell-fill fs-4"></i>
            <span v-if="notificationsCount > 0" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
              {{ notificationsCount }}
            </span>
          </Link>

          <div class="d-flex align-items-center gap-1">
            <div class="str">
  <span class="fw-bold fs-3" style="color: var(--bs-danger)" v-if="urgent">!</span>
  <i class="bi bi-fire fs-4"></i>
  <div class="str-tooltip">
    Keep the fire alive — one capsule away!🔥
  </div>
</div>
          <span 
            class="fw-bold" 
          >
            {{ streakDays }}
          </span>
        </div>


          <div class="position-relative">
            <img 
              :src="`../storage/${user.avatar}` || '/default-avatar.png'" 
              alt="Profile" 
              class="rounded-circle profile-pic" 
              width="35" 
              height="35"
              style="cursor: pointer"
              @click="toggleProfileCard"
            />

            <div v-if="showProfileCard" class="profile-card shadow-lg border rounded-4 p-3 bg-white text-dark position-absolute end-0 mt-2">
              <div class="d-flex align-items-center gap-3">
                <img 
                  :src="`../storage/${user.avatar}` || '/default-avatar.png'" 
                  alt="Profile" 
                  class="rounded-circle" 
                  width="50" 
                  height="50"
                />
                <div class="d-flex flex-column gap-0">
                  <h5 class="mb-0 fw-bold">{{ user.name }}</h5>
                  <Link :href="route('dashboard')" class="text-decoration-none">
                    <span class="fw-bold mb-0" style="font-size: 0.90rem; color: var(--yelloworange); text-decoration: underline; text-decoration-color: var(--yelloworange);">
                      Show Profile
                    </span>
                  </Link>
                </div>
              </div>
              <hr>
              <Link :href="route('logout')" method="post" class="btn btn-sm fw-bold w-100" style="background-color: white; color: var(--bs-danger); border: 2px solid var(--bs-danger);">
                <i class="bi bi-box-arrow-right"></i> Logout
              </Link>
              <Link 
                href="#" 
                class="btn btn-danger btn-sm fw-bold w-100 mt-2"
                @click.prevent="confirmDelete"
              >
                <i class="bi bi-trash"></i> Delete account
              </Link>

            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <!-- Mobile Navbar -->
  <nav v-else class="navbar navbar-light bg-success fixed-top d-flex justify-content-between px-3 py-2">
    <!-- Logo -->
     <div class="d-flex align-items-center gap-4">
        <div style="background-color: white;" class="rounded-pill text-center">
          <div class="d-flex align-items-center justify-content-center">
            <img 
              src="../../../../public/storage/logo/logo.png" 
              alt="Capselo Logo" 
              width="30"
            >
            <div class="ms-0 me-3">
              <h5 class="fw-bold mb-0">
                <span style="color: black; text-decoration: underline; text-decoration-color: var(--bs-success);">
                  Capselo</span>
                <span style="color: var(--bs-success);">.</span>
              </h5>
            </div>
          </div>
        </div>
      </div>
    <!-- Icons -->
    <div class="d-flex align-items-center gap-3">
      <Link :href="route('feed')" class="text-white fs-4">
        <i class="bi bi-house-door-fill"></i>
      </Link>
      <Link :href="route('premium')" class="text-white fs-4">
        <i class="bi bi-gem"></i>
      </Link>
      <Link :href="route('dashboard')" class="text-white">
        <img :src="`../storage/${user.avatar}` || '/default-avatar.png'" class="rounded-circle" width="35" height="35">
      </Link>
    </div>
  </nav>

  <main style="padding-top: 70px;">
    <slot></slot>
  </main>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { usePage, Link, router } from '@inertiajs/vue3'
import axios from 'axios'

const confirmDelete = () => {
  if (confirm("Are you sure you want to delete your account? This action cannot be undone.")) {
    router.delete(route('account.delete'))
  }
}

const page = usePage()
const notificationsCount = ref(page.props.auth?.user?.unread_notifications || 0)
const streakDays = ref(page.props.auth?.user?.streakDays || 0)
const user = page.props.auth?.user
const urgent = ref(false)

const fetchStreak = async () => {
  try {
    const response = await axios.get(route('user.streak'))
    streakDays.value = response.data.streak
    urgent.value = response.data.urgent
  } catch (error) {
    console.error('Error fetching streak:', error)
  }
}

onMounted(() => {
  fetchStreak() // call once on load
  console.log(urgent);
})



// Profile dropdown state
const showProfileCard = ref(false)
const toggleProfileCard = () => {
  showProfileCard.value = !showProfileCard.value
}

// Detect mobile
const isMobile = ref(window.innerWidth <= 991)
const handleResize = () => { isMobile.value = window.innerWidth <= 991 }
onMounted(() => window.addEventListener('resize', handleResize))
onUnmounted(() => window.removeEventListener('resize', handleResize))

// Close profile card on click outside
const handleClickOutside = (event) => {
  if (!event.target.closest('.profile-card') && !event.target.closest('.profile-pic')) {
    showProfileCard.value = false
  }
}
onMounted(() => document.addEventListener('click', handleClickOutside))
onUnmounted(() => document.removeEventListener('click', handleClickOutside))
</script>

<style scoped>
/* Desktop profile card */
.profile-card {
  width: 230px;
  z-index: 2000;
  animation: fadeIn 0.15s ease-in-out;
  word-wrap: break-word;          
  overflow-wrap: break-word;      
  white-space: normal;            
}

.profile-card h6,
.profile-card small {
  max-width: 160px;               
  overflow: hidden;
  text-overflow: ellipsis;
  display: block;
}

.profile-card .d-flex.align-items-center {
  flex-wrap: nowrap;
}

.profile-card hr {
  margin: 0.75rem 0;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-5px); }
  to { opacity: 1; transform: translateY(0); }
}
.str {
  position: relative;
  display: inline-block;
  cursor: pointer;
}

.str-tooltip {
  position: absolute;
  bottom: -38px; /* was -45px → move it a bit higher */
  left: 30%; /* keep it slightly left */
  transform: translateX(-50%) translateY(0);
  background: white;
  color: black;
  font-size: 14px;
  padding: 6px 10px;
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.25s ease, transform 0.25s ease;
  white-space: nowrap;
}

.str:hover .str-tooltip {
  opacity: 1;
  transform: translateX(-70%) translateY(2px); /* slightly down for animation */
}


</style>











