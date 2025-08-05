<x-app-layout class="bg-gradient-to-br from-slate-50 via-white to-slate-100 min-h-screen overflow-x-hidden">
    <div x-data="dashboard()" class="relative">
        <div class="fixed inset-0 pointer-events-none overflow-hidden">
            <template x-for="(blob, i) in bgBlobs" :key="i">
                <div :class="blob.class" :style="blob.style"></div>
            </template>
        </div>

        <header class="relative z-10 px-6 py-8">
            <div class="max-w-7xl mx-auto">
                <div class="glass rounded-3xl p-6 slide-in flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 gradient-animated rounded-2xl flex items-center justify-center rotate-slow">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-green-400 rounded-full pulse-glow"></div>
                        <span class="text-sm text-gray-600 font-medium">Live</span>
                    </div>
                </div>
            </div>
        </header>

        <section class="relative z-10 px-6 py-4">
            <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <template x-for="(card, index) in cards" :key="index">
                    <div class="glass rounded-3xl p-6 card-3d slide-in micro-bounce relative overflow-hidden"
                        :style="`animation-delay: ${index * 0.1}s`"
                        x-data="{ count: 0 }"
                        x-init="animateCount($el, card.value)">

                        <div class="absolute inset-0 rounded-3xl opacity-5" :class="card.gradient"></div>

                        <div class="relative z-10 space-y-4">
                            <div class="flex items-center justify-between">
                                <div :class="card.iconBg"
                                    class="w-14 h-14 rounded-2xl flex items-center justify-center pulse-glow"
                                    x-html="card.icon">
                                </div>
                                <div class="text-xs text-gray-500 font-medium uppercase" x-text="card.label"></div>
                            </div>

                            <div>
                                <div class="text-3xl font-bold text-gray-800 count-up"
                                    x-text="card.format ? formatNumber(count) : count"></div>
                                <div class="flex items-center space-x-2 text-xs text-gray-400">
                                    <span class="flex items-center text-green-600 font-medium space-x-1">
                                        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                                        <span>+12%</span>
                                    </span>
                                    <span>vs last month</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </section>

        <section class="relative z-10 px-6 py-8">
            <div class="max-w-7xl mx-auto glass rounded-3xl p-8 slide-in" style="animation-delay: 0.8s;">
                <div class="flex justify-between items-center mb-8">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 gradient-animated rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586l6.707 6.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">Recent Bookings</h2>
                            <p class="text-sm text-gray-500">Latest activity overview</p>
                        </div>
                    </div>
                    <button class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-xl font-medium micro-bounce hover:shadow-lg transition-all">
                        Add New
                    </button>
                </div>

                <div class="overflow-hidden rounded-2xl">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-50 to-gray-100 text-xs text-gray-600 uppercase tracking-wider">
                                <th class="px-6 py-4 text-left">Code</th>
                                <th class="px-6 py-4 text-left">User</th>
                                <th class="px-6 py-4 text-left">Hotel</th>
                                <th class="px-6 py-4 text-left">Status</th>
                                <th class="px-6 py-4 text-left">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <template x-for="(booking, index) in bookings" :key="index">
                                <tr class="table-row" :style="`animation-delay: ${index * 0.1}s`">
                                    <td class="px-6 py-4 font-medium text-gray-800" x-text="'#' + booking.code"></td>
                                    <td class="px-6 py-4 flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-pink-400 to-rose-400 flex items-center justify-center text-white font-bold text-sm" x-text="booking.user.charAt(0)"></div>
                                        <div>
                                            <div class="font-medium text-gray-800" x-text="booking.user"></div>
                                            <div class="text-sm text-gray-500">Customer</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-medium text-gray-800" x-text="booking.hotel"></td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full"
                                            :class="{
                                            'bg-green-100 text-green-800': booking.status==='Active',
                                            'bg-yellow-100 text-yellow-800': booking.status==='Pending',
                                            'bg-gray-100 text-gray-800': booking.status==='Completed'
                                        }"
                                            x-text="booking.status">
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <button class="text-indigo-600 hover:text-indigo-800 font-medium micro-bounce">View Details</button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>


    <script>
        function dashboard() {
            return {
                bgBlobs: [{
                        class: 'absolute w-96 h-96 gradient-animated rounded-full blur-3xl opacity-10 morph -top-20 -left-20'
                    },
                    {
                        class: 'absolute w-80 h-80 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full blur-3xl opacity-10 float top-1/3 -right-20'
                    },
                    {
                        class: 'absolute w-72 h-72 bg-gradient-to-r from-blue-400 to-cyan-400 rounded-full blur-3xl opacity-10 morph bottom-0 left-1/4'
                    },
                ],
                cards: [{
                        label: 'Total Users',
                        value: 1250,
                        icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path></svg>',
                        gradient: 'bg-gradient-to-br from-indigo-500 to-purple-600',
                        iconBg: 'bg-gradient-to-r from-indigo-500 to-purple-500'
                    },
                    {
                        label: 'Total Bookings',
                        value: 892,
                        icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>',
                        gradient: 'bg-gradient-to-br from-green-500 to-emerald-600',
                        iconBg: 'bg-gradient-to-r from-green-500 to-emerald-500'
                    },
                    {
                        label: 'Available Rooms',
                        value: 47,
                        icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>',
                        gradient: 'bg-gradient-to-br from-blue-500 to-cyan-600',
                        iconBg: 'bg-gradient-to-r from-blue-500 to-cyan-500'
                    },
                    {
                        label: 'Monthly Revenue',
                        value: 125000,
                        format: true,
                        icon: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path></svg>',
                        gradient: 'bg-gradient-to-br from-pink-500 to-rose-600',
                        iconBg: 'bg-gradient-to-r from-pink-500 to-rose-500'
                    }
                ],
                bookings: [{
                        code: '001',
                        user: 'John Doe',
                        hotel: 'Grand Palace',
                        status: 'Active'
                    },
                    {
                        code: '002',
                        user: 'Sarah Wilson',
                        hotel: 'Ocean View',
                        status: 'Pending'
                    },
                    {
                        code: '003',
                        user: 'Mike Johnson',
                        hotel: 'Mountain Resort',
                        status: 'Active'
                    },
                    {
                        code: '004',
                        user: 'Emma Davis',
                        hotel: 'City Center',
                        status: 'Completed'
                    },
                    {
                        code: '005',
                        user: 'Alex Brown',
                        hotel: 'Seaside Villa',
                        status: 'Active'
                    }
                ],
                animateCount(el, target) {
                    let count = 0;
                    const step = target / 100;
                    const interval = setInterval(() => {
                        count = Math.min(count + step, target);
                        el.__x.$data.count = Math.floor(count);
                        if (count >= target) clearInterval(interval);
                    }, 16);
                },
                formatNumber(num) {
                    return new Intl.NumberFormat('en-US', {
                        style: 'currency',
                        currency: 'USD',
                        minimumFractionDigits: 0
                    }).format(num);
                }
            }
        }
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        * {
            font-family: 'Inter', sans-serif;
        }

        .card-3d {
            transform-style: preserve-3d;
            perspective: 1000px;
        }

        .card-3d:hover {
            transform: rotateX(5deg) rotateY(5deg) translateZ(20px);
        }

        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .neo {
            background: linear-gradient(145deg, #f0f0f0, #cacaca);
            box-shadow: 20px 20px 60px #bebebe, -20px -20px 60px #ffffff;
        }

        .gradient-animated {
            background: linear-gradient(270deg, #667eea, #764ba2, #f093fb, #f5576c, #4facfe, #00f2fe);
            background-size: 1200% 1200%;
            animation: gradientShift 8s ease infinite;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            33% {
                transform: translateY(-10px) rotate(1deg);
            }

            66% {
                transform: translateY(5px) rotate(-1deg);
            }
        }

        .float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes pulseGlow {

            0%,
            100% {
                box-shadow: 0 0 20px rgba(99, 102, 241, 0.3);
                transform: scale(1);
            }

            50% {
                box-shadow: 0 0 40px rgba(99, 102, 241, 0.6);
                transform: scale(1.05);
            }
        }

        .pulse-glow {
            animation: pulseGlow 3s ease-in-out infinite;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translate3d(0, 40px, 0);
            }

            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }

        .slide-in {
            animation: slideInUp 0.8s ease-out forwards;
        }

        @keyframes morph {

            0%,
            100% {
                border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
            }

            50% {
                border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%;
            }
        }

        .morph {
            animation: morph 8s ease-in-out infinite;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .rotate-slow {
            animation: rotate 20s linear infinite;
        }

        @keyframes countUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .count-up {
            animation: countUp 0.5s ease-out;
        }

        .table-row {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .table-row:hover {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.05), rgba(168, 85, 247, 0.05));
            transform: translateX(5px);
            box-shadow: 0 4px 20px rgba(99, 102, 241, 0.1);
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 10px;
        }

        .micro-bounce {
            transition: transform 0.2s ease;
        }

        .micro-bounce:hover {
            transform: translateY(-2px);
        }

        .neon {
            text-shadow: 0 0 10px rgba(99, 102, 241, 0.8), 0 0 20px rgba(99, 102, 241, 0.6);
        }
    </style>
</x-app-layout>