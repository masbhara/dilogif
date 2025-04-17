<template>
  <MainLayout>
    <CompanyHistory />
    <TeamMembers />
    <Values />
    <ContactCTA />
  </MainLayout>
</template>

<script setup>
import MainLayout from '@/components/layout/MainLayout.vue'
import CompanyHistory from '@/components/about/CompanyHistory.vue'
import TeamMembers from '@/components/about/TeamMembers.vue'
import Values from '@/components/about/Values.vue'
import ContactCTA from '@/components/home/ContactCTA.vue'
</script> 