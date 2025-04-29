<template>
  <section class="py-20 bg-background">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-3xl font-bold text-center text-foreground mb-4">
        Solusi Untuk Bisnis Anda
      </h2>
      <p class="text-center text-foreground mb-12">
        Jadikan bisnis Anda agar semakin dikenal publik dengan profesional
      </p>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-background p-6 rounded-lg shadow-sm">
          <img src="/images/web-design.svg" alt="Web Design" class="w-full h-auto mb-4" />
          <h3 class="text-xl font-semibold mb-2 text-foreground">Web Design</h3>
          <p class="text-foreground mb-4">
            Website yang menarik untuk meningkatkan kredibilitas bisnis Anda
          </p>
          <a href="#" class="text-primary font-medium hover:underline">Learn More →</a>
        </div>
        <div class="bg-background p-6 rounded-lg shadow-sm">
          <img src="/images/logo-design.svg" alt="Logo Design" class="w-full h-auto mb-4" />
          <h3 class="text-xl font-semibold mb-2 text-foreground">Logo Design</h3>
          <p class="text-foreground mb-4">
            Logo yang profesional untuk meningkatkan brand awareness
          </p>
          <a href="#" class="text-primary font-medium hover:underline">Learn More →</a>
        </div>
        <div class="bg-background p-6 rounded-lg shadow-sm">
          <img src="/images/content-creation.svg" alt="Content Creation" class="w-full h-auto mb-4" />
          <h3 class="text-xl font-semibold mb-2 text-foreground">Content Creation</h3>
          <p class="text-foreground mb-4">
            Konten yang menarik untuk meningkatkan engagement
          </p>
          <a href="#" class="text-primary font-medium hover:underline">Learn More →</a>
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