<template>
  <section class="overflow-hidden bg-muted py-24 sm:py-32">
    <div class="container">
      <div class="mx-auto grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 sm:gap-y-20 lg:mx-0 lg:max-w-none lg:grid-cols-2">
        <div>
          <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">About Our Company</h2>
          <p class="mt-6 text-lg leading-8 text-muted-foreground">
            We are a team of passionate professionals dedicated to delivering high-quality solutions for our clients.
            With years of experience in the industry, we understand what it takes to succeed in today's competitive market.
          </p>
          <dl class="mt-10 max-w-xl space-y-8 text-base leading-7 text-muted-foreground lg:max-w-none">
            <div v-for="feature in features" :key="feature.name" class="relative pl-9">
              <dt class="inline font-semibold text-foreground">
                <component :is="feature.icon" class="absolute left-1 top-1 h-5 w-5 text-primary" />
                {{ feature.name }}
              </dt>
              <dd class="inline">{{ feature.description }}</dd>
            </div>
          </dl>
        </div>
        <div class="relative">
          <img
            src="/images/about-image.jpg"
            alt="About our company"
            class="w-[48rem] max-w-none rounded-xl shadow-xl ring-1 ring-gray-400/10 sm:w-[57rem]"
          />
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
const features = [
  {
    name: 'Expert Team.',
    description: ' Our team consists of highly skilled professionals with years of experience.',
    icon: 'UsersIcon'
  },
  {
    name: 'Quality Assurance.',
    description: ' We maintain the highest standards of quality in all our projects.',
    icon: 'CheckCircleIcon'
  },
  {
    name: 'Customer Support.',
    description: ' We provide excellent customer support and are always here to help.',
    icon: 'HeadsetIcon'
  }
]
</script> 