<script setup>
const team = [
  {
    name: 'John Doe',
    position: 'CEO & Founder',
    image: '/images/team/john-doe.jpg',
    bio: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'
  },
  {
    name: 'Jane Smith',
    position: 'CTO',
    image: '/images/team/jane-smith.jpg',
    bio: 'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
  },
  // Tambahkan anggota tim lainnya sesuai kebutuhan
]
</script>

<template>
  <section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center">
        <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl">Our Team</h2>
        <p class="mt-4 text-lg text-gray-500">Meet the amazing people behind our success</p>
      </div>
      <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
        <div v-for="member in team" :key="member.name" class="bg-white rounded-lg shadow-lg overflow-hidden">
          <img :src="member.image" :alt="member.name" class="w-full h-64 object-cover">
          <div class="p-6">
            <h3 class="text-xl font-semibold text-gray-900">{{ member.name }}</h3>
            <p class="mt-1 text-sm text-gray-500">{{ member.position }}</p>
            <p class="mt-3 text-base text-gray-600">{{ member.bio }}</p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template> 