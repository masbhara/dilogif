<template>
  <section class="py-24 sm:py-32">
    <div class="container">
      <div class="mx-auto max-w-2xl text-center">
        <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">What Our Clients Say</h2>
        <p class="mt-6 text-lg leading-8 text-muted-foreground">
          Don't just take our word for it. Here's what our clients have to say about our services.
        </p>
      </div>
      <div class="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 sm:mt-20 sm:grid-cols-2 lg:mx-0 lg:max-w-none lg:grid-cols-3">
        <div v-for="testimonial in testimonials" :key="testimonial.author" class="flex flex-col justify-between rounded-2xl bg-muted p-8">
          <div>
            <div class="flex gap-x-1">
              <StarIcon v-for="i in 5" :key="i" class="h-5 w-5 text-yellow-400" />
            </div>
            <p class="mt-4 text-lg leading-6 text-muted-foreground">{{ testimonial.content }}</p>
          </div>
          <div class="mt-6 flex items-center gap-x-4">
            <img :src="testimonial.image" :alt="testimonial.author" class="h-10 w-10 rounded-full bg-muted-foreground" />
            <div>
              <div class="font-semibold">{{ testimonial.author }}</div>
              <div class="text-sm text-muted-foreground">{{ testimonial.role }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { StarIcon } from '@heroicons/vue/24/solid'

const testimonials = [
  {
    content: "Working with this team has been an absolute pleasure. They delivered our project on time and exceeded our expectations.",
    author: "Sarah Johnson",
    role: "CEO, TechStart",
    image: "/images/testimonials/sarah.jpg"
  },
  {
    content: "The level of expertise and professionalism is outstanding. They truly understand their craft and deliver exceptional results.",
    author: "Michael Chen",
    role: "CTO, InnovateCorp",
    image: "/images/testimonials/michael.jpg"
  },
  {
    content: "Their attention to detail and commitment to quality is impressive. I highly recommend their services to anyone.",
    author: "Emily Davis",
    role: "Marketing Director, GrowthCo",
    image: "/images/testimonials/emily.jpg"
  }
]
</script> 