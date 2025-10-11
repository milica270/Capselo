<template>
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
          <!-- Feed -->
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
          <!-- Notifications -->
          <Link :href="route('notifications')" class="text-white position-relative">
            <i class="bi bi-bell-fill fs-4"></i>
            <span 
              v-if="notificationsCount > 0" 
              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
              {{ notificationsCount }}
            </span>
          </Link>

          <!-- Streak -->
          <div class="d-flex align-items-center gap-1">
            <i class="bi bi-fire fs-4"></i>
            <span class="fw-bold">{{ streakDays }}</span>
          </div>

          <!-- Profile dropdown -->
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

            <!-- Profile card dropdown -->
            <div 
              v-if="showProfileCard" 
              class="profile-card shadow-lg border rounded-4 p-3 bg-white text-dark position-absolute end-0 mt-2"
            >
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
                <span 
                    class="fw-bold mb-0" 
                    style="font-size: 0.90rem; color: var(--yelloworange); text-decoration: underline; text-decoration-color: var(--yelloworange);">
                    Show Profile
                </span>
                </Link>


                </div>
              </div>

<hr>

<!-- Capsules section -->
<div class="capsules-section">
  <small class="fw-bold ms-2" style="color: var(--darkGrey)" >Capsules</small>
  
  <div class="d-flex justify-content-between mt-2">
    <!-- New -->
    <div class="card-it mini-card text-center flex-fill mx-1 p-2">
      <i class="bi bi-plus-circle fs-5"></i>
      <div class="small fst-italic">New</div>
    </div>
    
    <!-- Archive -->
    <div class="card-it mini-card text-center flex-fill mx-1 p-2">
      <i class="bi bi-archive fs-5"></i>
      <div class="small fst-italic">Archive</div>
    </div>

    <!-- Drafts -->
    <div class="card-it mini-card text-center flex-fill mx-1 p-2">
      <i class="bi bi-file-earmark-text fs-5"></i>
      <div class="small fst-italic">Drafts</div>
    </div>
  </div>
</div>


              <hr>

              <div class="d-flex flex-column">
                <Link 
                :href="route('feed')" 
                style="color: var(--darkGrey)"
                class="profile-tab py-1 px-2 mb-1 text-decoration-none rounded fw-bold"
                >
                <small>Badges</small>
                </Link>
                <Link 
                :href="route('feed')" 
                style="color: var(--darkGrey)"
                class="profile-tab py-1 px-2 mb-1 text-decoration-none rounded fw-bold"
                >
                <small>Friends</small>
                </Link>
            </div>

            
              
              <Link 
                :href="route('logout')" 
                method="post" 
                class="btn btn-danger btn-sm fw-bold w-100"
              >
              <i class="bi bi-box-arrow-right"></i>
                Logout
              </Link>
            </div>
          </div>
        </div>
      </div>
      
    </div>
  </nav>


   <nav v-else class="navbar navbar-light navbar-expand-lg bg-success px-4 py-2 text-white d-flex flex-column fixed-top">
<div class="d-flex justify-content-between" style="width:100%">
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


      <button v-if="!isMenuOpen" class="btn" type="button" @click.prevent="changeS()" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
    <i class="bi bi-list text-black fs-5"></i>
  </button>
  <Link v-else class="btn" type="button" :href="page.url" @click="closeMenu"><i class="bi bi-list text-black fs-5"></i></Link>
</div>

<div class="collapse mt-4" id="collapseExample" style="width:100%">
    
    <div class="d-flex justify-content-between gap-2 align-items-center" style="width:100%">
            <div class="d-flex align-items-center gap-3 p-2 rounded pe-5" style="background-color: white; border: 2px solid black;">
                <img 
                  :src="`../storage/${user.avatar}` || '/default-avatar.png'" 
                  alt="Profile" 
                  class="rounded-circle" 
                  width="70" 
                  height="70"
                />
                <div class="d-flex flex-column gap-0">
                  <h4 class="text-black mb-0 fw-bold">{{ user.name }} {{ user.lastName }}</h4>
                  <Link @click="closeMenu" :href="route('dashboard')" class="text-decoration-none">
                <span 
                    class="fw-bold mb-0" 
                    style="font-size: 0.90rem; color: var(--yelloworange); text-decoration: underline; text-decoration-color: var(--yelloworange);">
                    Show Profile
                </span>
                </Link>


                </div>
              </div>
              <div class="d-flex flex-column">
                <Link style="text-decoration:none" :href="route('notifications')" class="text-white d-flex align-items-center gap-1">
            <i class="bi bi-bell-fill fs-4"></i> 
            <span class="fw-bold"> {{ notificationsCount }}</span>
          </Link>

          <!-- Streak -->
          <div class="d-flex align-items-center gap-1">
            <i class="bi bi-fire fs-4"></i>
            <span class="fw-bold"> {{ streakDays }}</span>
          </div>
              </div>
    </div>
  
              <hr>


              <!-- Capsules section -->
<div class="capsules-section">
  <h4 class="fw-bold ms-2" style="color: white" >Capsules</h4>
  
  <div class="d-flex justify-content-between mt-2">
    <!-- New -->
    <div class="card-it mini-card text-center flex-fill mx-1 p-2" style="border: 1px solid black">
      <i class="bi bi-plus-circle fs-5"></i>
      <div class="small fst-italic">New</div>
    </div>
    
    <!-- Archive -->
    <div class="card-it mini-card text-center flex-fill mx-1 p-2" style="border: 1px solid black">
      <i class="bi bi-archive fs-5"></i>
      <div class="small fst-italic">Archive</div>
    </div>

    <!-- Drafts -->
    <div class="card-it mini-card text-center flex-fill mx-1 p-2" style="border: 1px solid black">
      <i class="bi bi-file-earmark-text fs-5"></i>
      <div class="small fst-italic">Drafts</div>
    </div>
  </div>
</div>

   <hr>

<div class="d-flex flex-column">
                <Link 
                @click="closeMenu"
                :href="route('feed')" 
                style="color: var(--darkGrey)"
                class="profile-tab py-1 px-2 mb-1 text-decoration-none rounded fw-bold"
                >
                <small class="text-white">Badges</small>
                </Link>
                <Link 
                @click="closeMenu"
                :href="route('feed')" 
                style="color: var(--darkGrey)"
                class="profile-tab py-1 px-2 mb-1 text-decoration-none rounded fw-bold"
                >
                <small class="text-white">Friends</small>
                </Link>
                <Link 
                @click="closeMenu"
                :href="route('feed')" 
                style="color: var(--darkGrey)"
                class="profile-tab py-1 px-2 mb-1 text-decoration-none rounded fw-bold"
                >
                <i class="bi bi-house-door-fill fs-4 text-white"></i>
                <small class="text-white"> Feed</small>
                </Link>
                <Link 
                @click="closeMenu"
                :href="route('premium')" 
                style="color: var(--darkGrey)"
                class="profile-tab py-1 px-2 mb-1 text-decoration-none rounded fw-bold"
                >
                <i class="bi bi-gem fs-4 text-white"></i> 
                <small class="text-white"> Premium</small>
                </Link>
            </div>


<hr>

<Link 
                :href="route('logout')" 
                method="post" 
                class="btn btn-danger btn-sm fw-bold w-100"
              >
              <i class="bi bi-box-arrow-right"></i>
                Logout
              </Link>

   
</div>

      

   </nav>

   <main style="padding-top: 70px;">
        <slot></slot>
    </main>
  
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'
import { Collapse } from 'bootstrap'

const collapseInstance = ref(null)
const isMenuOpen = ref(false)

onMounted(() => {
  const collapseEl = document.getElementById('collapseExample')
  if (collapseEl) collapseInstance.value = new Collapse(collapseEl, { toggle: false })
})

const changeS = () => {
    isMenuOpen.value = true
    
}

// Function to close collapse programmatically
const closeMenu = () => {
    isMenuOpen.value = false
  if (collapseInstance.value) collapseInstance.value.hide()
}


const windowWidth = ref(window.innerWidth)
const isMobile = ref(window.innerWidth <= 991)

const handleResize = () => {
  windowWidth.value = window.innerWidth
  isMobile.value = windowWidth.value <= 991
}

onMounted(() => {
  window.addEventListener('resize', handleResize)
})

onUnmounted(() => {
  window.removeEventListener('resize', handleResize)
})

const page = usePage()
const notificationsCount = ref(page.props.auth?.user?.unread_notifications || 0)
const streakDays = ref(page.props.auth?.user?.streak_days || 0)
const user = page.props.auth?.user

// Profile dropdown state
const showProfileCard = ref(false)
const toggleProfileCard = () => {
  showProfileCard.value = !showProfileCard.value
}

// Close the card when clicking outside
const handleClickOutside = (event) => {
  if (!event.target.closest('.profile-card') && !event.target.closest('.profile-pic')) {
    showProfileCard.value = false
  }
}

onMounted(() => document.addEventListener('click', handleClickOutside))
onUnmounted(() => document.removeEventListener('click', handleClickOutside))
</script>

<style scoped>
.navbar .dropdown-menu {
  min-width: 180px;
}

.dropdown-menu .dropdown-item:hover {
  background-color: var(--bs-success);
  color: white;
}

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

/* Make avatar row neat */
.profile-card .d-flex.align-items-center {
  flex-wrap: nowrap;
}

/* Optional – center and clean up card */
.profile-card hr {
  margin: 0.75rem 0;
}

.card-it {
  border: 1px solid var(--lightGrey);
  border-radius: 10px;
  cursor: pointer;
  color: var(--lightGrey); /* default color */
  transition: all 0.3s ease; /* smooth hover transition */
  background-color: white; /* optional */
}

/* Hover effect */
.card-it:hover {
  color: black; /* changes text and icon */
  border: 1px solid var(--darkGrey) !important;  /* dark grey border on hover */
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25); /* shadow */
}

.card-it:hover i,
.card-it:hover div {
  color: var(--darkGrey) !important;
}

/* Ensure icon and text inherit the color */
.card-it i,
.card-it div {
  transition: color 0.3s ease;
  color: inherit;
}


.profile-tab:hover {
  background-color: var(--lightGrey);
  color: white !important;
  cursor: pointer;
}




/* Animation */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-5px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Responsive right items */
@media (max-width: 991px) {
  .right-items {
    justify-content: center !important;
    width: 100%;
  }
}
</style>










