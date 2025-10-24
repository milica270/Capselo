<template>
  <Head>
    <title>home</title>
  </Head>

  <div class="container-fluid vh-100">
    <div class="row h-100">
      <!-- Left Side -->
      <div class="col-md-6 d-flex justify-content-center align-items-center bg-light">
        <h2 class="text-muted">Welcome to Capselo</h2>
      </div>

      <!-- Right Side -->
      <div class="col-md-6 d-flex flex-column justify-content-center align-items-center">
        
        <!-- Show this if NOT logged in -->
        <template v-if="!authUser">
          <!-- Logo + Title -->
          <div class="text-center mb-1">
            <div class="d-flex align-items-center justify-content-center">
              <img src="../../../public/storage/logo/logo.png" alt="Capselo Logo" width="150" />
              <div class="ms-0">
                <h1 class="fw-bold mb-0">
                  <span style="color: black; text-decoration: underline; text-decoration-color: var(--bs-success);">Capselo</span>
                  <span style="color: var(--bs-success);">.</span>
                </h1>
                <p class="mt-1 text-muted fst-italic small text-end">
                  your story, one capsule at a time
                </p>
              </div>
            </div>
          </div>

          <!-- Login Form -->
          <form class="w-75" @submit.prevent="submit">
            <div class="mb-3">
              <label for="email" class="form-label fw-bold">Email address</label>
              <input type="email" v-model="form.email" class="form-control" id="email" placeholder="Enter email" />
            </div>
            <div class="mb-3">
              <label for="password" class="form-label fw-bold">Password</label>
              <input type="password" v-model="form.password" class="form-control" id="password" placeholder="Password" />
              
              
            </div>
            <button type="submit" class="btn btn-success w-100" :disabled="form.processing">Login</button>
          </form>

          <!-- Register Link -->
          <div class="mt-3 text-center">
            <p>
              Don’t have an account?
              <Link :href="route('register')" class="fw-bold text-success text-decoration-none">Register</Link>
            </p>
          </div>
        </template>

        <!-- Show this if LOGGED IN -->
        <template v-else>
          <div class="text-center mb-1">
            <div class="d-flex align-items-center justify-content-center">
              <img src="../../../public/storage/logo/logo.png" alt="Capselo Logo" width="130" />
              <div class="ms-0">
                <h1 class="fw-bold mb-0">
                  <span style="color: black; text-decoration: underline; text-decoration-color: var(--bs-success);">Capselo</span>
                  <span style="color: var(--bs-success);">.</span>
                </h1>
                <p class="mt-1 text-muted fst-italic small text-end">
                  your story, one capsule at a time
                </p>
              </div>
            </div>
          </div>
        </template>

      </div>
    </div>
  </div>
</template>

<script setup>
import { useForm, usePage, Link } from '@inertiajs/vue3'

const form = useForm({
  email: null,
  password: null,
  remember: null,
})

// Access user from shared props
const page = usePage()
const authUser = page.props.auth?.user

const submit = () => {
  form.post(route('authenticate'), {
    onError: (errors) => {
      // Reset sensitive fields
      form.reset('password', 'remember')

      // Alert the user about the error
      if (errors) {
        const messages = Object.values(errors).flat().join('\n')
        alert(messages)
      } else {
        alert("There was an error. Please try again.")
      }
    },
  })
}


</script>


