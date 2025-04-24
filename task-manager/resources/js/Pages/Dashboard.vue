<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="text-2xl font-bold text-[#1e144f]">Dashboard</div>
        </template>

        <div class="px-4 sm:px-6 lg:px-8 py-6 bg-[#f9f9fb]">
            <!-- Projects Section -->
            <section class="mb-8">
                <h3 class="text-lg font-bold text-[#1e144f] mb-4">Projects</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    <div class="bg-white p-6 rounded-xl shadow-sm flex items-center gap-4">
                        <div class="bg-[#fcd34d] p-3 rounded-lg">
                            <i class="fas fa-pencil-ruler text-[#1e144f] text-xl"></i>
                        </div>
                        <div>
                            <div class="text-[#1e144f] font-semibold">UI Design</div>
                            <div class="text-sm text-gray-500">5 projects</div>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-sm flex items-center gap-4">
                        <div class="bg-[#fcd34d] p-3 rounded-lg">
                            <i class="fas fa-paint-brush text-[#1e144f] text-xl"></i>
                        </div>
                        <div>
                            <div class="text-[#1e144f] font-semibold">Illustration</div>
                            <div class="text-sm text-gray-500">1 project</div>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-sm flex items-center gap-4">
                        <div class="bg-[#fcd34d] p-3 rounded-lg">
                            <i class="fas fa-code text-[#1e144f] text-xl"></i>
                        </div>
                        <div>
                            <div class="text-[#1e144f] font-semibold">Development</div>
                            <div class="text-sm text-gray-500">3 projects</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Today Section -->
            <section class="mb-8">
                <h3 class="text-lg font-bold text-[#1e144f] mb-4">Today</h3>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-white p-4 rounded-xl shadow-sm flex justify-between items-center">
                        <div>
                            <div class="text-[#fbbf24] font-bold text-lg">23 Mon</div>
                            <div class="font-medium text-[#1e144f]">Hotel management app</div>
                            <div class="text-sm text-gray-500">Development • 3:00 pm</div>
                        </div>
                        <div class="flex -space-x-2">
                            <img src="https://i.pravatar.cc/40?u=1" class="w-8 h-8 rounded-full border-2 border-white" />
                            <img src="https://i.pravatar.cc/40?u=2" class="w-8 h-8 rounded-full border-2 border-white" />
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-xl shadow-sm flex justify-between items-center">
                        <div>
                            <div class="text-[#1e144f] font-bold text-lg">25 Wed</div>
                            <div class="font-medium text-[#1e144f]">Portfolio website</div>
                            <div class="text-sm text-gray-500">UI Design • 10:00 am</div>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                </div>
            </section>

            <!-- Team Members -->
            <section>
                <h3 class="text-lg font-bold text-[#1e144f] mb-4">Team Members</h3>
                <div class="bg-white p-4 rounded-xl shadow-sm space-y-4">
                    <div class="flex items-center gap-4">
                        <img src="https://i.pravatar.cc/40?u=3" class="w-10 h-10 rounded-full" />
                        <div>
                            <div class="text-[#1e144f] font-semibold">Nick Smith</div>
                            <div class="text-sm text-gray-500">UI Designer</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <img src="https://i.pravatar.cc/40?u=4" class="w-10 h-10 rounded-full" />
                        <div>
                            <div class="text-[#1e144f] font-semibold">Francine Cook</div>
                            <div class="text-sm text-gray-500">Developer</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <img src="https://i.pravatar.cc/40?u=5" class="w-10 h-10 rounded-full" />
                        <div>
                            <div class="text-[#1e144f] font-semibold">Mary Ann</div>
                            <div class="text-sm text-gray-500">Animator</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <img src="https://i.pravatar.cc/40?u=6" class="w-10 h-10 rounded-full" />
                        <div>
                            <div class="text-[#1e144f] font-semibold">Eugene Stewart</div>
                            <div class="text-sm text-gray-500">UX Researcher</div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </AuthenticatedLayout>
</template>
