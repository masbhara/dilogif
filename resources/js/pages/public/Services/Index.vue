<template>
  <MainLayout>
    <ServiceHero />
    <ServiceList />
    <ServiceFeatures />
    <ContactCTA />
  </MainLayout>
</template>

<script setup>
import MainLayout from '@/components/layout/MainLayout.vue'
import ServiceHero from '@/components/services/ServiceHero.vue'
import ServiceList from '@/components/services/ServiceList.vue'
import ServiceFeatures from '@/components/services/ServiceFeatures.vue'
import ContactCTA from '@/components/home/ContactCTA.vue'
</script> 