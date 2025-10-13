import { Toast } from 'bootstrap'

export function showToast(message, type = 'success') {
  let toastContainer = document.querySelector('.toast-container')

  // Create the container if it doesn't exist
  if (!toastContainer) {
    toastContainer = document.createElement('div')
    toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3'
    toastContainer.style.zIndex = '2000'
    document.body.appendChild(toastContainer)
  }

  // Determine the background class
  const bgClass = type === 'success' ? 'bg-primary' : `bg-${type}`

  // Create the toast element
  const toastEl = document.createElement('div')
  toastEl.className = `toast align-items-center text-white border-0 ${bgClass}`
  toastEl.role = 'alert'
  toastEl.ariaLive = 'assertive'
  toastEl.ariaAtomic = 'true'
  toastEl.innerHTML = `
    <div class="d-flex">
      <div class="toast-body fw-bold">${message}</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  `

  // Append to container
  toastContainer.appendChild(toastEl)

  // Initialize and show the toast
  const toast = new Toast(toastEl, { delay: 3000 })
  toast.show()

  // Automatically remove the toast element after it hides
  toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove())
}


