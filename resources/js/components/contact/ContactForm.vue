<template>
  <section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center">
        <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl">Contact Us</h2>
        <p class="mt-4 text-lg text-gray-500">We'd love to hear from you</p>
      </div>
      
      <div class="mt-12 max-w-lg mx-auto">
        <form @submit.prevent="submit" class="grid grid-cols-1 gap-y-6">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input 
              type="text" 
              id="name"
              v-model="form.name"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
            <div v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</div>
          </div>

          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input 
              type="email" 
              id="email"
              v-model="form.email"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
            <div v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</div>
          </div>

          <div>
            <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
            <input 
              type="tel" 
              id="phone"
              v-model="form.phone"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
            <div v-if="form.errors.phone" class="mt-1 text-sm text-red-600">{{ form.errors.phone }}</div>
          </div>

          <div>
            <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
            <textarea 
              id="message"
              v-model="form.message"
              rows="4"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            ></textarea>
            <div v-if="form.errors.message" class="mt-1 text-sm text-red-600">{{ form.errors.message }}</div>
          </div>

          <div>
            <button 
              type="submit"
              :disabled="form.processing"
              class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
            >
              {{ form.processing ? 'Sending...' : 'Send Message' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </section>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  name: '',
  email: '',
  phone: '',
  message: ''
})

const submit = () => {
  form.post(route('contact.store'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
    }
  })
}
</script> 