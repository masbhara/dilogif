<template>
  <section class="py-24 sm:py-32">
    <div class="container">
      <div class="mx-auto max-w-2xl text-center">
        <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">What We Offer</h2>
        <p class="mt-6 text-lg leading-8 text-muted-foreground">
          Explore our comprehensive range of services designed to meet your business needs.
        </p>
      </div>
      <div class="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 sm:mt-20 sm:grid-cols-2 lg:mx-0 lg:max-w-none lg:grid-cols-3">
        <div v-for="service in services" :key="service.title" class="flex flex-col">
          <div class="mb-6 flex h-10 w-10 items-center justify-center rounded-lg bg-primary">
            <component :is="service.icon" class="h-6 w-6 text-primary-foreground" />
          </div>
          <h3 class="text-lg font-semibold">{{ service.title }}</h3>
          <p class="mt-2 text-muted-foreground">{{ service.description }}</p>
          <ul class="mt-4 space-y-2">
            <li v-for="feature in service.features" :key="feature" class="flex items-center">
              <CheckIcon class="h-5 w-5 text-primary" />
              <span class="ml-2 text-sm text-muted-foreground">{{ feature }}</span>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { CheckIcon } from '@heroicons/vue/24/outline'
import {
  CodeBracketIcon,
  DevicePhoneMobileIcon,
  PaintBrushIcon,
  CloudIcon,
  MegaphoneIcon,
  LightBulbIcon
} from '@heroicons/vue/24/outline'

const services = [
  {
    title: 'Web Development',
    description: 'Custom web applications built with modern technologies.',
    icon: CodeBracketIcon,
    features: [
      'Responsive Design',
      'Custom CMS Development',
      'E-commerce Solutions',
      'API Integration'
    ]
  },
  {
    title: 'Mobile Apps',
    description: 'Native and cross-platform mobile applications.',
    icon: DevicePhoneMobileIcon,
    features: [
      'iOS Development',
      'Android Development',
      'Cross-platform Solutions',
      'App Maintenance'
    ]
  },
  {
    title: 'UI/UX Design',
    description: 'Beautiful and intuitive user interfaces.',
    icon: PaintBrushIcon,
    features: [
      'User Research',
      'Wireframing',
      'Prototyping',
      'Visual Design'
    ]
  },
  {
    title: 'Cloud Solutions',
    description: 'Scalable cloud infrastructure and services.',
    icon: CloudIcon,
    features: [
      'Cloud Migration',
      'Server Management',
      'DevOps Services',
      'Security Solutions'
    ]
  },
  {
    title: 'Digital Marketing',
    description: 'Strategic digital marketing campaigns.',
    icon: MegaphoneIcon,
    features: [
      'SEO Optimization',
      'Social Media Marketing',
      'Content Strategy',
      'Analytics & Reporting'
    ]
  },
  {
    title: 'Consulting',
    description: 'Expert business and technology consulting.',
    icon: LightBulbIcon,
    features: [
      'Technology Assessment',
      'Digital Strategy',
      'Process Optimization',
      'Team Training'
    ]
  }
]
</script> 