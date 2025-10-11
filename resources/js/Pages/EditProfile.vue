<template>
  <Head>
    <title>Edit Profile</title>
  </Head>

  <div class="container-fluid mt-3 bg-light">
    <div class="row">
      <div class="col-md-1"></div>

      <!-- Form Column -->
      <div class="col-md-3">
        <form @submit.prevent="submit" class="d-flex flex-column gap-3 p-3 bg-white rounded shadow-sm">
          
          <!-- Avatar Preview + Upload -->
          

          <div class=" d-flex justify-content-between align-items-start">

  <!-- Left side: Avatar and Upload -->
  <div class="d-flex flex-column align-items-center gap-2 me-4">
    <img 
      class="rounded-4"
      width="90" 
      height="90"
      :src="form.preview || (user.avatar ? `../storage/${user.avatar}` : '/default-avatar.png')" 
      style="object-fit: cover; display: block;"
    >

    <div class="text-center">
      <input type="file" id="avatar" hidden @change="handleAvatarChange">
      <label for="avatar" style="font-size: 0.85rem; cursor: pointer; text-decoration: underline;">
        Change Photo
      </label>
      <p class="text-danger mb-0" style="font-size: 0.75rem;">{{ form.errors.avatar }}</p>
    </div>
  </div>

  <!-- Right side: Name and Last Name -->
  <div class="d-flex flex-row gap-2 flex-wrap">
    <input 
      type="text"
      v-model="form.name"
      :placeholder="user.name"
      class="form-control"
      style="border:1px solid var(--darkGrey)"
    >
    <input 
      type="text"
      v-model="form.lastName"
      :placeholder="user.lastName"
      class="form-control"
      style="border:1px solid var(--darkGrey)"
    >
  </div>

</div>
   
          <!-- Birthdate -->
           <div class="d-flex align-items-center gap-3">
      <i class="bi bi-cake2-fill fs-3"></i>
      <div>
          <input type="date" style="border:1px solid var(--darkGrey)" v-model="form.birthdate" :placeholder="user.birthdate" class="form-control" />
        
        </div>
        </div>
          <!-- Location -->
           <div class="d-flex align-items-center gap-3" >
      <i class="bi bi-geo-alt-fill fs-3"></i>
      <div class="d-flex flex-column gap-1">
          <input type="text" style="border:1px solid var(--darkGrey)" v-model="form.country" :placeholder="user.country" class="form-control" />
          <input type="text" style="border:1px solid var(--darkGrey)" v-model="form.city" :placeholder="user.city" class="form-control" />
</div>
</div>
          <!-- Role & School/Job -->
          


<!-- Role & School/Job -->
<div class="d-flex align-items-center gap-3">
  <i class="bi bi-briefcase-fill fs-3"></i>

  <div class="flex-grow-1 d-flex flex-column gap-2">
    <!-- Role select -->
    <select 
      v-model="form.role" 
      class="form-select"
      style="border:1px solid var(--darkGrey)"
    >
      <option value="school">School</option>
      <option value="job">Job</option>
    </select>

    <!-- Conditional fields -->
    <div v-if="form.role === 'school'">
      <select 
        v-model="form.schoolType" 
        class="form-select"
        style="border:1px solid var(--darkGrey)"
      >
        <option value="elementary">Elementary School</option>
        <option value="highschool">High School</option>
        <option value="university">University</option>
      </select>
    </div>

    

    <div v-else-if="form.role === 'job'">
      <input 
        type="text" 
        v-model="form.jobType" 
        :placeholder="user.jobType || 'Enter your job type'" 
        class="form-control"
        style="border:1px solid var(--darkGrey)"
      />
    </div>
  </div>
</div>






          <!-- Submit -->
          <button type="submit" class="btn fw-bold fs-6" style="color: var(--darkGreen); background-color: var(--lightGreen)">Save Changes</button>

        </form>
      </div>

      <div class="col-md-7">..</div>
      <div class="col-md-1"></div>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
  user: Object
})

const form = useForm({
  name: props.user.name,
  lastName: props.user.lastName,
  birthdate: props.user.birthdate,
  country: props.user.country,
  city: props.user.city,
  role: props.user.role,
  schoolType: props.user.schoolType,
  jobType: props.user.jobType,
  avatar: props.user.avatar,
  preview: null
})

const submit = () => {
  form.put(route('users.update', props.user.id));
}

// Handle avatar preview
const handleAvatarChange = (e) => {
  const file = e.target.files[0]
  if (file) {
    form.avatar = file
    form.preview = URL.createObjectURL(file)
  }
}

const formatDate = (dateString) => {
  if (!dateString) return '-';
  const date = new Date(dateString);
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return new Intl.DateTimeFormat('en-US', options).format(date);
};
</script>
