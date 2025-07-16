<?php
include "components/header.php";
include('backend/class.php');
$db = new global_class();
?>

<!-- Hero Section -->
<section class="bg-gray-100">
  <div class="container mx-auto px-6 py-20 grid md:grid-cols-2 gap-12 items-center">
    
    <!-- Text Content -->
    <div class="space-y-6 text-center md:text-left">
      <h1 class="text-4xl md:text-5xl font-bold text-gray-900 leading-tight">
        Empower Your Brand<br><span class="text-blue-600">with Smart Technology</span>
      </h1>
      <p class="text-gray-700 text-lg max-w-md mx-auto md:mx-0">
        Innovative solutions, clean design, and scalable platforms that drive success.
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
        <a href="#services" class="px-6 py-3 bg-blue-600 text-white rounded-full font-medium hover:bg-blue-700 transition">
          Explore Services
        </a>
        <a href="#contact" class="px-6 py-3 border border-blue-600 text-blue-600 rounded-full hover:bg-blue-100 transition">
          Contact Us
        </a>
      </div>
    </div>

    <!-- Hero Image -->
    <div class="relative flex justify-center">
      <img src="assets/img/banner.jpg" alt="Hero Image" class="rounded-2xl shadow-2xl w-full max-w-md md:max-w-full">
    </div>
  </div>
</section>

<!-- Feature Highlights -->
<section class="py-20 bg-white" id="services">
  <div class="container mx-auto px-6 text-center">
    <h2 class="text-4xl font-bold text-gray-800 mb-14">What Sets Us Apart</h2>
    <div class="grid md:grid-cols-3 gap-8">

      <!-- Feature 1 -->
      <div class="bg-gray-50 p-8 rounded-2xl shadow hover:shadow-xl transition duration-300">
        <div class="text-blue-600 mb-4">
          <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 8c-1.105 0-2 .895-2 2 0 1.5 2 3 2 3s2-1.5 2-3c0-1.105-.895-2-2-2z" />
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 2a10 10 0 100 20 10 10 0 000-20z" />
          </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-800">Strategic Solutions</h3>
        <p class="text-gray-600 mt-2">Future-ready systems tailored for sustainable growth.</p>
      </div>

      <!-- Feature 2 -->
      <div class="bg-gray-50 p-8 rounded-2xl shadow hover:shadow-xl transition duration-300">
        <div class="text-blue-600 mb-4">
          <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M3 10h2l1 2h13l1-2h2m-2 2l-1 9H6l-1-9m5-4h4a1 1 0 001-1V4H9v3a1 1 0 001 1z" />
          </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-800">Elegant Interfaces</h3>
        <p class="text-gray-600 mt-2">User-centric designs that offer clarity and engagement.</p>
      </div>

      <!-- Feature 3 -->
      <div class="bg-gray-50 p-8 rounded-2xl shadow hover:shadow-xl transition duration-300">
        <div class="text-blue-600 mb-4">
          <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 14l9-5-9-5-9 5 9 5z" />
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 14l6.16-3.422a12.083 12.083 0 01-6.16 6.574A12.083 12.083 0 015.84 10.578L12 14z" />
          </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-800">24/7 Support</h3>
        <p class="text-gray-600 mt-2">Our expert team ensures stability and quick assistance.</p>
      </div>

    </div>
  </div>
</section>

<!-- Call to Action Section -->
<section class="bg-gray-700 py-20 text-white text-center">
  <div class="container mx-auto px-6">
    <h2 class="text-4xl font-bold mb-4">Ready to Build the Future?</h2>
    <p class="mb-6 text-lg">Let’s start your next digital transformation project together.</p>
    <a href="#contact"
      class="inline-block px-8 py-3 bg-white text-blue-700 rounded-full font-semibold hover:bg-blue-100 transition">
      Get in Touch
    </a>
  </div>
</section>

<?php include "components/footer.php"; ?>
