<template>
  <section class="py-24 sm:py-32">
    <div class="container">
      <div class="mx-auto max-w-2xl text-center">
        <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">Our Services</h2>
        <p class="mt-6 text-lg leading-8 text-muted-foreground">
          We offer a wide range of services to help your business grow and succeed.
        </p>
      </div>
      <div class="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 sm:mt-20 sm:grid-cols-2 lg:mx-0 lg:max-w-none lg:grid-cols-3">
        <div v-for="service in services" :key="service.title" class="flex flex-col">
          <div class="mb-6 flex h-10 w-10 items-center justify-center rounded-lg bg-primary">
            <component :is="service.icon" class="h-6 w-6 text-primary-foreground" />
          </div>
          <h3 class="text-lg font-semibold">{{ service.title }}</h3>
          <p class="mt-2 text-muted-foreground">{{ service.description }}</p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
const services = [
  {
    title: 'Web Development',
    description: 'Custom web applications built with modern technologies.',
    icon: 'CodeIcon'
  },
  {
    title: 'Mobile Apps',
    description: 'Native and cross-platform mobile applications.',
    icon: 'SmartphoneIcon'
  },
  {
    title: 'UI/UX Design',
    description: 'Beautiful and intuitive user interfaces.',
    icon: 'PaletteIcon'
  },
  {
    title: 'Cloud Solutions',
    description: 'Scalable cloud infrastructure and services.',
    icon: 'CloudIcon'
  },
  {
    title: 'Digital Marketing',
    description: 'Strategic digital marketing campaigns.',
    icon: 'MegaphoneIcon'
  },
  {
    title: 'Consulting',
    description: 'Expert business and technology consulting.',
    icon: 'LightbulbIcon'
  }
]
</script> 