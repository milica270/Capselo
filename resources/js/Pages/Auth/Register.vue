<template>
  <Head>
    <title>Register</title>
  </Head>

  <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <!-- Logo + Title -->
<div class="text-center">
  <div class="d-flex align-items-center justify-content-center">
    <!-- Logo -->
    <img 
      src="../../../../public/storage/logo/logo.png" 
      alt="Capselo Logo" 
      width="90"
    >

    <!-- Title -->
    <div class="ms-0">
      <h1 class="fw-bold mb-0">
        <span style="color: black; text-decoration: underline; text-decoration-color: var(--bs-success);">
          Capselo</span>
        <span style="color: var(--bs-success);">.</span>
      </h1>
    </div>
  </div>
</div>
        </div>
      <div class="col-md-6">

        

<!-- Subtitle -->
<h2 class="text-center text-muted mt-4">Register</h2>


        <!-- Progress Bar -->
        <div class="progress mb-4" style="height: 8px;">
          <div
            class="progress-bar bg-success"
            role="progressbar"
            :style="{ width: progress + '%' }"
            :aria-valuenow="progress"
            aria-valuemin="0"
            aria-valuemax="100">
          </div>
        </div>

        <!-- Multi-step form -->
        <form @submit.prevent="submit">

          <!-- Step 1 -->
          <div v-if="step === 1">
            <div class="mb-3">
              <label for="name" class="form-label">First Name</label>
              <input type="text" v-model="form.name" class="form-control" id="name" required>
            </div>

            <div class="mb-3">
              <label for="lastName" class="form-label">Last Name</label>
              <input type="text" v-model="form.lastName" class="form-control" id="lastName" required>
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Gmail</label>
              <input type="email" v-model="form.email" class="form-control" id="email" required>
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" v-model="form.password" class="form-control" id="password" required>
            </div>

            <div class="mb-3">
              <label for="password_confirmation" class="form-label">Confirm Password</label>
              <input type="password" v-model="form.password_confirmation" class="form-control" id="password_confirmation" required>
            </div>
          </div>

          <!-- Step 2 -->
          <div v-if="step === 2">
            <div class="mb-3">
              <label for="birthdate" class="form-label">Birthdate</label>
              <input type="date" v-model="form.birthdate" class="form-control" id="birthdate" required>
            </div>

            <div class="mb-3">
              <label for="country" class="form-label">Country</label>
              <select v-model="form.country" class="form-select" id="country" required>
                <option value="" disabled>Select your country</option>
                <option v-for="country in countries" :key="country" :value="country">
                  {{ country }}
                </option>
              </select>
            </div>

            <div class="mb-3">
              <label for="city" class="form-label">City</label>
              <input type="text" v-model="form.city" class="form-control" id="city" required>
            </div>
          </div>

          <!-- Step 3 -->
          <div v-if="step === 3">
            <div class="mb-3">
              <label class="form-label">Are you a Student or Worker?</label>
              <div>
                <input type="radio" id="school" value="school" v-model="form.role">
                <label for="school" class="me-3">School</label>

                <input type="radio" id="job" value="job" v-model="form.role">
                <label for="job">Job</label>
              </div>
            </div>

            <!-- If School -->
            <div v-if="form.role === 'school'" class="mb-3">
              <label for="schoolType" class="form-label">Select School Type</label>
              <select v-model="form.schoolType" class="form-select" id="schoolType">
                <option value="elementary">Elementary</option>
                <option value="highschool">High School</option>
                <option value="university">University</option>
              </select>
            </div>

            <!-- If Job -->
            <div v-if="form.role === 'job'" class="mb-3">
              <label for="jobType" class="form-label">Select Job</label>
              <select v-model="form.jobType" class="form-select" id="jobType">
                <option value="it">IT</option>
                <option value="teacher">Teacher</option>
                <option value="doctor">Doctor</option>
                <option value="engineer">Engineer</option>
                <option value="other">Other</option>
              </select>
            </div>

            <!-- Step 3: Profile Picture Upload -->
            <div class="mb-3">
              <label class="form-label fw-bold">Profile Picture</label>
              <div class="d-flex flex-column align-items-left gap-1">
                <!-- Image Preview Box -->
                <div class="border bg-light position-relative"
                     style="width: 140px; height: 140px; border: 2px solid #6c757d;">
                  <img :src="form.preview ?? '../../../../public/storage/avatars/default.jpg'" 
                       style="width: 100%; height: 100%; object-fit: cover; display: block;">
                </div>

                <!-- Upload Text -->
                <div class="d-flex flex-column justify-content-center">
                  <input type="file" id="avatar" hidden @change="change">
                  <label for="avatar" style="font-size: 0.85rem; cursor: pointer; text-decoration: underline;">
                    Upload Photo
                  </label>
                  <p class="text-danger mb-0" style="font-size: 0.75rem;">{{ form.errors.avatar }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Navigation buttons -->
          <div class="d-flex justify-content-between mt-3">
            <button type="button" class="btn btn-outline-secondary" @click="prevStep" :disabled="step === 1">
              Back
            </button>
            <button v-if="step < 3" type="button" class="btn btn-success" @click="nextStep">
              Next
            </button>
            <button v-if="step === 3" type="submit" class="btn btn-success">
              Register
            </button>
          </div>
        </form>

        <!-- Login link -->
        <p class="text-center mt-3">
          Already have an account?
          <Link :href="route('home')" class="text-decoration-none fw-bold text-success">Login</Link>
        </p>
      </div>
      <div class="col-md-3"></div>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const step = ref(1)

const form = useForm({
  name: null,
  lastName: null,
  email: null,
  password: null,
  password_confirmation: null,
  birthdate: null,
  country: null,
  city: null,
  role: null,
  schoolType: null,
  jobType: null,
  avatar: null,       
  preview: null,
})

// Progress bar % (3 steps = 33, 66, 100)
const progress = computed(() => (step.value / 3) * 100)

const nextStep = () => {
  if (step.value < 3) step.value++
}

const prevStep = () => {
  if (step.value > 1) step.value--
}

const change = (e) => {
  form.avatar = e.target.files[0]
  form.preview = URL.createObjectURL(e.target.files[0])
}

const submit = () => {
  form.post(route('store'), {
    onError: () => form.reset('password', 'password_confirmation'),
  })
}



const countries = [
  "Afghanistan","Albania","Algeria","Andorra","Angola","Antigua and Barbuda",
  "Argentina","Armenia","Australia","Austria","Azerbaijan","Bahamas","Bahrain",
  "Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bhutan","Bolivia",
  "Bosnia and Herzegovina","Botswana","Brazil","Brunei","Bulgaria","Burkina Faso",
  "Burundi","Cabo Verde","Cambodia","Cameroon","Canada","Central African Republic",
  "Chad","Chile","China","Colombia","Comoros","Congo (Congo-Brazzaville)",
  "Costa Rica","Croatia","Cuba","Cyprus","Czechia","Democratic Republic of the Congo",
  "Denmark","Djibouti","Dominica","Dominican Republic","Ecuador","Egypt","El Salvador",
  "Equatorial Guinea","Eritrea","Estonia","Eswatini","Ethiopia","Fiji","Finland","France",
  "Gabon","Gambia","Georgia","Germany","Ghana","Greece","Grenada","Guatemala","Guinea",
  "Guinea-Bissau","Guyana","Haiti","Holy See","Honduras","Hungary","Iceland","India",
  "Indonesia","Iran","Iraq","Ireland","Israel","Italy","Jamaica","Japan","Jordan","Kazakhstan",
  "Kenya","Kiribati","Kuwait","Kyrgyzstan","Laos","Latvia","Lebanon","Lesotho","Liberia",
  "Libya","Liechtenstein","Lithuania","Luxembourg","Madagascar","Malawi","Malaysia","Maldives",
  "Mali","Malta","Marshall Islands","Mauritania","Mauritius","Mexico","Micronesia","Moldova",
  "Monaco","Mongolia","Montenegro","Morocco","Mozambique","Myanmar","Namibia","Nauru","Nepal",
  "Netherlands","New Zealand","Nicaragua","Niger","Nigeria","North Korea","North Macedonia",
  "Norway","Oman","Pakistan","Palau","Palestine State","Panama","Papua New Guinea","Paraguay",
  "Peru","Philippines","Poland","Portugal","Qatar","Romania","Russia","Rwanda","Saint Kitts and Nevis",
  "Saint Lucia","Saint Vincent and the Grenadines","Samoa","San Marino","Sao Tome and Principe",
  "Saudi Arabia","Senegal","Serbia","Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia",
  "Solomon Islands","Somalia","South Africa","South Korea","South Sudan","Spain","Sri Lanka","Sudan",
  "Suriname","Sweden","Switzerland","Syria","Tajikistan","Tanzania","Thailand","Timor-Leste","Togo",
  "Tonga","Trinidad and Tobago","Tunisia","Turkey","Turkmenistan","Tuvalu","Uganda","Ukraine",
  "United Arab Emirates","United Kingdom","United States","Uruguay","Uzbekistan","Vanuatu","Venezuela",
  "Vietnam","Yemen","Zambia","Zimbabwe"
]

</script>
