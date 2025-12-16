<template>
    <Head>
    <title>verify email</title>
  </Head>
  <div class="min-vh-100 position-relative bg-light">

    <!-- Logo + Title at top-left -->
    <div class="position-absolute top-0 start-0 m-3 d-flex align-items-center">
      <img src="/public/storage/logo/logo.png" alt="Capselo Logo" width="60" />
      <div class="ms-2">
        <h3 class="fw-bold mb-0">
          <span style="color: black; text-decoration: underline; text-decoration-color: var(--bs-success);">Capselo</span>
          <span style="color: var(--bs-success);">.</span>
        </h3>
      </div>
    </div>

    <!-- Verification Card Centered -->
    <div class="d-flex flex-column justify-content-center align-items-center min-vh-100">
      <div class="card shadow-sm p-4" style="max-width: 400px; width: 100%;">
        <h2 class="fw-bold text-success mb-3 text-center">Verify Your Email</h2>

        <p class="text-center mb-3">
          Thanks for signing up! Check your email for the verification link to complete registration.
        </p>

        <p class="text-center text-success mb-3" v-if="status">
          {{ status }}
        </p>

        <!-- Countdown timer -->
        <p class="text-center text-danger fw-bold mb-4" v-if="!isVerified">
          Time left to verify: {{ minutes }}:{{ seconds }}
        </p>

        <div class="d-flex flex-column gap-2">
          <button @click="resendEmail" class="btn btn-success" :disabled="sending || isVerified">
            <span v-if="sending">Sending...</span>
            <span v-else>Resend Verification Email</span>
          </button>

          <button @click="manualCancel" class="btn btn-outline-secondary mt-2" :disabled="isVerified">
            Cancel
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import axios from 'axios'

const sending = ref(false)
const status = ref(null)
const isVerified = ref(false)
const timeLeft = ref(180) // 3 minutes in seconds
const minutes = computed(() => String(Math.floor(timeLeft.value / 60)).padStart(2, '0'))
const seconds = computed(() => String(timeLeft.value % 60).padStart(2, '0'))

let timerInterval = null
let pollInterval = null

// Resend verification email
const resendEmail = async () => {
  sending.value = true
  try {
    await Inertia.post(route('verification.send'))
    status.value = 'Verification link sent! Check your email.'
  } catch (err) {
    console.error(err)
    status.value = 'Failed to send verification email. Try again.'
  } finally {
    sending.value = false
  }
}

// Cancel / delete account
const performCancel = async (skipConfirm = false) => {
  if (!skipConfirm && !confirm('Are you sure you want to cancel and delete your account?')) return
  try {
    await Inertia.delete(route('account.delete'), { preserveState: false })
  } catch (err) {
    console.error(err)
  }
}

const manualCancel = () => performCancel(false)

// Start countdown timer
const startTimer = () => {
  timerInterval = setInterval(() => {
    if (isVerified.value) {
      clearInterval(timerInterval)
      timerInterval = null
      return
    }

    if (timeLeft.value > 0) {
      timeLeft.value--
    } else {
      clearInterval(timerInterval)
      timerInterval = null
      performCancel(true) // auto cancel
    }
  }, 1000)
}

// Poll server for verification status every 5 seconds
const startPolling = () => {
  pollInterval = setInterval(async () => {
    try {
      const { data } = await axios.get(route('verification.status')) // new route that returns { verified: true/false }
      if (data.verified) {
        isVerified.value = true
        clearInterval(timerInterval)
        clearInterval(pollInterval)
      }
    } catch (err) {
      console.error('Polling error:', err)
    }
  }, 5000)
}

onMounted(() => {
  startTimer()
  startPolling()
})

onUnmounted(() => {
  if (timerInterval) clearInterval(timerInterval)
  if (pollInterval) clearInterval(pollInterval)
})
</script>

