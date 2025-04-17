<template>
  <section class="py-24 sm:py-32">
    <div class="container">
      <div class="mx-auto max-w-2xl text-center">
        <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">Our Values</h2>
        <p class="mt-6 text-lg leading-8 text-muted-foreground">
          These core values guide everything we do and help us maintain the highest standards.
        </p>
      </div>
      <div class="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 sm:mt-20 sm:grid-cols-2 lg:mx-0 lg:max-w-none lg:grid-cols-3">
        <div v-for="value in values" :key="value.name" class="flex flex-col">
          <div class="mb-6 flex h-10 w-10 items-center justify-center rounded-lg bg-primary">
            <component :is="value.icon" class="h-6 w-6 text-primary-foreground" />
          </div>
          <h3 class="text-lg font-semibold">{{ value.name }}</h3>
          <p class="mt-2 text-muted-foreground">{{ value.description }}</p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import {
  HeartIcon,
  LightBulbIcon,
  ShieldCheckIcon,
  UserGroupIcon,
  ChartBarIcon,
  SparklesIcon
} from '@heroicons/vue/24/outline'

const values = [
  {
    name: 'Passion',
    description: 'We are passionate about what we do and strive for excellence in every project.',
    icon: HeartIcon
  },
  {
    name: 'Innovation',
    description: 'We constantly explore new ideas and technologies to deliver cutting-edge solutions.',
    icon: LightBulbIcon
  },
  {
    name: 'Integrity',
    description: 'We maintain the highest ethical standards and build trust with our clients.',
    icon: ShieldCheckIcon
  },
  {
    name: 'Collaboration',
    description: 'We believe in the power of teamwork and value diverse perspectives.',
    icon: UserGroupIcon
  },
  {
    name: 'Growth',
    description: 'We are committed to continuous learning and improvement.',
    icon: ChartBarIcon
  },
  {
    name: 'Excellence',
    description: 'We strive for excellence in everything we do, from code to customer service.',
    icon: SparklesIcon
  }
]
</script> 