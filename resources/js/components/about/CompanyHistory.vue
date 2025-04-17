<template>
  <section class="py-24 sm:py-32">
    <div class="container">
      <div class="mx-auto max-w-2xl text-center">
        <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">Our Journey</h2>
        <p class="mt-6 text-lg leading-8 text-muted-foreground">
          From humble beginnings to industry leadership, discover our company's story.
        </p>
      </div>
      <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
        <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-3">
          <div v-for="milestone in milestones" :key="milestone.year" class="flex flex-col">
            <dt class="text-base font-semibold leading-7">
              <span class="text-primary">{{ milestone.year }}</span>
            </dt>
            <dd class="mt-1 flex flex-auto flex-col text-base leading-7 text-muted-foreground">
              <p class="flex-auto">{{ milestone.description }}</p>
            </dd>
          </div>
        </dl>
      </div>
    </div>
  </section>
</template>

<script setup>
const milestones = [
  {
    year: '2015',
    description: 'Founded with a vision to revolutionize the industry through innovative solutions.'
  },
  {
    year: '2018',
    description: 'Expanded our services and opened new offices to serve clients worldwide.'
  },
  {
    year: '2023',
    description: 'Achieved industry recognition and continued to grow our team of experts.'
  }
]
</script> 