<template>
  <section class="relative isolate overflow-hidden bg-primary">
    <div class="container py-24 sm:py-32">
      <div class="mx-auto max-w-2xl text-center">
        <h2 class="text-3xl font-bold tracking-tight text-primary-foreground sm:text-4xl">
          Ready to Get Started?
        </h2>
        <p class="mx-auto mt-6 max-w-xl text-lg leading-8 text-primary-foreground/80">
          Contact us today to discuss your project and discover how we can help you achieve your goals.
        </p>
        <div class="mt-10 flex items-center justify-center gap-x-6">
          <a
            href="/contact"
            class="rounded-md bg-white px-3.5 py-2.5 text-sm font-semibold text-primary shadow-sm hover:bg-primary-foreground/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
          >
            Contact Us
          </a>
          <a href="/services" class="text-sm font-semibold leading-6 text-primary-foreground">
            Learn more <span aria-hidden="true">→</span>
          </a>
        </div>
      </div>
    </div>
    <svg
      viewBox="0 0 1024 1024"
      class="absolute left-0 top-1/2 -z-10 h-[64rem] w-[64rem] -translate-y-1/2 [mask-image:radial-gradient(closest-side,white,transparent)]"
      aria-hidden="true"
    >
      <circle cx="512" cy="512" r="512" fill="url(#gradient)" fill-opacity="0.7" />
      <defs>
        <radialGradient id="gradient">
          <stop stop-color="#7775D6" />
          <stop offset="1" stop-color="#E935C1" />
        </radialGradient>
      </defs>
    </svg>
  </section>
</template> 