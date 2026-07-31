<template>
  <div>
    <slot />
  </div>
</template>

<script setup>
// Landing page layout - minimal, no admin chrome
useHead({
  link: [
    { rel: 'shortcut icon', href: '/images/9.png' }
  ],
  script: [
    { src: 'https://cdn.tailwindcss.com', tagPosition: 'head' }
  ]
})
</script>
