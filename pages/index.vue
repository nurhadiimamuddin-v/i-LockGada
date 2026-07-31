<template>
  <div class="relative flex flex-col select-none cursor-not-allowed h-screen w-full font-inter bg-white overflow-hidden">
    <!-- Grid Background -->
    <div class="absolute inset-0 z-0 pointer-events-none" style="background-image: linear-gradient(to right, rgba(0,0,0,0.05) 1px, transparent 1px); background-size: 20vw 100%;"></div>

    <main class="flex-1 w-full max-w-[1600px] mx-auto px-4 sm:px-8 relative flex flex-col items-center justify-center pt-20 pb-16 h-full z-10">
        
      <!-- Big Typography -->
      <h1 class="absolute top-[2vh] left-0 w-full text-center text-[20vw] font-medium leading-none tracking-tighter text-[#0d0d0d] z-10 whitespace-nowrap pointer-events-none" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
          i-LockGada
      </h1>

      <!-- The 3D Stone -->
      <div class="absolute inset-0 flex items-center justify-center z-20 pointer-events-none pt-[45vh]">
          <img src="/images/pegadaian_keyhole_v6.png" alt="3D Keyhole Stone" class="w-auto h-[110vh] drop-shadow-2xl object-contain animate-[float_6s_ease-in-out_infinite]">
      </div>

      <!-- Bottom Left Text & Button -->
      <div class="absolute bottom-8 left-6 md:bottom-16 md:left-12 z-30 max-w-[280px] md:max-w-[360px]">
          <p class="text-[18px] md:text-[22px] leading-tight text-[#0d0d0d] mb-6" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; letter-spacing: -0.02em;">
              Kelola pendataan barang masuk dan keluar dengan lebih cerdas, serta kendalikan dan buka loker langsung melalui platform ini.
          </p>
          <NuxtLink to="/login" class="pointer-events-auto cursor-pointer inline-block text-white text-sm font-medium px-7 py-3 transition-opacity hover:opacity-90 rounded-md" style="background-color: #0aaa4e; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
              Get Started
          </NuxtLink>
      </div>

      <!-- Bottom Right Logos -->
      <div class="absolute bottom-12 right-8 md:bottom-24 md:right-16 z-30 w-[330px] md:w-[440px] overflow-hidden pointer-events-none">
          <div class="absolute left-0 top-0 w-[130px] md:w-[170px] h-full z-40 bg-white/60" style="-webkit-backdrop-filter: grayscale(100%); backdrop-filter: grayscale(100%); -webkit-mask-image: linear-gradient(to right, black 60%, transparent 100%); mask-image: linear-gradient(to right, black 60%, transparent 100%);"></div>
          <div class="absolute right-0 top-0 w-[130px] md:w-[170px] h-full z-40 bg-white/60" style="-webkit-backdrop-filter: grayscale(100%); backdrop-filter: grayscale(100%); -webkit-mask-image: linear-gradient(to left, black 60%, transparent 100%); mask-image: linear-gradient(to left, black 60%, transparent 100%);"></div>

          <div class="marquee-content flex items-center w-max relative z-30">
              <div class="flex items-center gap-[30px] pr-[30px] md:gap-[40px] md:pr-[40px]">
                  <img src="/images/peg.png" alt="Logo PEG" class="w-[90px] md:w-[120px] h-[40px] md:h-[50px] object-contain">
                  <img src="/images/44.png" alt="Logo 44" class="w-[90px] md:w-[120px] h-[40px] md:h-[50px] object-contain">
                  <img src="/images/P.png" alt="Logo P" class="w-[90px] md:w-[120px] h-[40px] md:h-[50px] object-contain">
              </div>
              <div class="flex items-center gap-[30px] pr-[30px] md:gap-[40px] md:pr-[40px]">
                  <img src="/images/peg.png" alt="Logo PEG" class="w-[90px] md:w-[120px] h-[40px] md:h-[50px] object-contain">
                  <img src="/images/44.png" alt="Logo 44" class="w-[90px] md:w-[120px] h-[40px] md:h-[50px] object-contain">
                  <img src="/images/P.png" alt="Logo P" class="w-[90px] md:w-[120px] h-[40px] md:h-[50px] object-contain">
              </div>
          </div>
      </div>
    </main>
  </div>
</template>

<script setup>
definePageMeta({
  layout: 'default'
})
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap');

.font-inter {
  font-family: 'Inter', sans-serif;
}
@keyframes float {
  0% { transform: translateY(0px); }
  50% { transform: translateY(-20px); }
  100% { transform: translateY(0px); }
}
.animate-\[float_6s_ease-in-out_infinite\] {
  animation: float 6s ease-in-out infinite;
}
@keyframes marqueeScroll {
  0% { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}
.marquee-content {
  animation: marqueeScroll 12s linear infinite;
  will-change: transform;
}
</style>
