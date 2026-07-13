@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 flex relative" x-data="{ sidebarOpen: false, sidebarCollapsed: false }">

    <!-- Desktop Sidebar -->
    <aside :class="sidebarCollapsed ? 'w-[80px]' : 'w-[280px]'" class="hidden lg:flex flex-col bg-white border-r border-slate-200 text-slate-700 fixed h-full z-20 shadow-sm transition-all duration-300 overflow-x-hidden">
        
        <!-- Logo Header -->
        <div class="h-20 flex items-center px-3 border-b border-slate-200/50 shrink-0 transition-all"
             :class="sidebarCollapsed ? 'justify-center' : 'justify-between'">

            <!-- Collapsed state: logo + maximize icon side by side (horizontal) -->
            <template x-if="sidebarCollapsed">
                <button @click="sidebarCollapsed = false"
                        title="Perbesar Menu"
                        class="flex flex-row items-center justify-center gap-1.5 group w-full">
                    <!-- Logo -->
                    <div class="bg-white p-1 rounded-lg shadow-sm border border-slate-100 group-hover:shadow-md transition-all duration-200 shrink-0">
                        <img src="{{ asset('image/LOGO UCI.jpeg') }}" alt="Logo" class="w-7 h-7 object-contain rounded">
                    </div>
                    <!-- Maximize icon — right of logo -->
                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-blue-50 border border-blue-100 group-hover:bg-[#003d7c] group-hover:border-[#003d7c] transition-all duration-200 shrink-0">
                        <svg class="w-3 h-3 text-[#003d7c] group-hover:text-white transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                        </svg>
                    </span>
                </button>
            </template>

            <!-- Expanded state: logo + brand + collapse button -->
            <template x-if="!sidebarCollapsed">
                <div class="flex items-center justify-between w-full gap-2">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="bg-white p-1 rounded-lg shadow-sm border border-slate-100 shrink-0">
                            <img src="{{ asset('image/LOGO UCI.jpeg') }}" alt="Logo" class="w-8 h-8 object-contain rounded">
                        </div>
                        <div class="whitespace-nowrap" x-transition.opacity.duration.300ms>
                            <h1 class="text-sm font-extrabold text-[#003d7c] leading-tight">PT. UCI</h1>
                            <p class="text-[10px] text-slate-500 font-semibold tracking-wider uppercase">Portal Dashboard</p>
                        </div>
                    </div>
                    <!-- Collapse / minimize button -->
                    <button @click="sidebarCollapsed = true"
                            title="Perkecil Menu"
                            class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition-all focus:outline-none flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                        </svg>
                    </button>
                </div>
            </template>

        </div>

        <!-- Navigation Links -->
        <nav class="flex-grow p-4 space-y-1 overflow-y-auto overflow-x-hidden z-10 custom-scrollbar">
            @if(Auth::user()->role === 'superadmin')
                <!-- SUPERADMIN SIDEBAR LINKS -->
                <div class="border-b border-slate-200/50 pb-1 mb-1">
                    <a href="{{ route('superadmin.dashboard') }}" title="Kelola Akun" class="flex items-center px-4 py-3 rounded-xl transition-all {{ Request::routeIs('superadmin.*') ? 'bg-[#003d7c] text-white shadow-lg shadow-blue-900/20' : 'text-slate-600 hover:bg-blue-50 hover:text-[#003d7c] hover:shadow-md border border-transparent hover:border-blue-100' }}">
                        <svg class="w-5 h-5 shrink-0" :class="sidebarCollapsed ? 'mx-auto' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms class="font-semibold text-sm whitespace-nowrap">Kelola Akun</span>
                    </a>
                </div>

            @elseif(Auth::user()->role === 'hrd')
                <!-- HRD SIDEBAR LINKS -->
                <div class="border-b border-slate-200/50 pb-1 mb-1">
                    <a href="{{ route('hrd.dashboard') }}" title="Dashboard" class="flex items-center px-4 py-3 rounded-xl transition-all {{ Request::routeIs('hrd.dashboard') ? 'bg-[#003d7c] text-white shadow-lg shadow-blue-900/20' : 'text-slate-600 hover:bg-blue-50 hover:text-[#003d7c] hover:shadow-md border border-transparent hover:border-blue-100' }}">
                        <svg class="w-5 h-5 shrink-0" :class="sidebarCollapsed ? 'mx-auto' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path></svg>
                        <span x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms class="font-semibold text-sm whitespace-nowrap">Dashboard</span>
                    </a>
                </div>
                
                <div class="border-b border-slate-200/50 pb-1 mb-1">
                    <a href="{{ route('hrd.hiring') }}" title="HIRING" class="flex items-center px-4 py-3 rounded-xl transition-all {{ Request::routeIs('hrd.hiring') ? 'bg-[#003d7c] text-white shadow-lg shadow-blue-900/20' : 'text-slate-600 hover:bg-blue-50 hover:text-[#003d7c] hover:shadow-md border border-transparent hover:border-blue-100' }}">
                        <svg class="w-5 h-5 shrink-0" :class="sidebarCollapsed ? 'mx-auto' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms class="font-semibold text-sm whitespace-nowrap">HIRING</span>
                    </a>
                </div>

                <div class="border-b border-slate-200/50 pb-1 mb-1">
                    <a href="{{ route('hrd.pelamar-aktif') }}" title="Data Pelamar" class="flex items-center px-4 py-3 rounded-xl transition-all {{ Request::routeIs('hrd.pelamar-aktif') ? 'bg-[#003d7c] text-white shadow-lg shadow-blue-900/20' : 'text-slate-600 hover:bg-blue-50 hover:text-[#003d7c] hover:shadow-md border border-transparent hover:border-blue-100' }}">
                        <svg class="w-5 h-5 shrink-0" :class="sidebarCollapsed ? 'mx-auto' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms class="font-semibold text-sm whitespace-nowrap">Data Pelamar</span>
                    </a>
                </div>

                <div class="border-b border-slate-200/50 pb-1 mb-1">
                    <a href="{{ route('hrd.partners.index') }}" title="Data Mitra" class="flex items-center px-4 py-3 rounded-xl transition-all {{ Request::routeIs('hrd.partners.*') ? 'bg-[#003d7c] text-white shadow-lg shadow-blue-900/20' : 'text-slate-600 hover:bg-blue-50 hover:text-[#003d7c] hover:shadow-md border border-transparent hover:border-blue-100' }}">
                        <svg class="w-5 h-5 shrink-0" :class="sidebarCollapsed ? 'mx-auto' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms class="font-semibold text-sm whitespace-nowrap">Data Mitra</span>
                    </a>
                </div>

                <div class="border-b border-slate-200/50 pb-1 mb-1">
                    <a href="{{ route('hrd.kriteria.index') }}" title="Kriteria Kandidat" class="flex items-center px-4 py-3 rounded-xl transition-all {{ Request::routeIs('hrd.kriteria.*') ? 'bg-[#003d7c] text-white shadow-lg shadow-blue-900/20' : 'text-slate-600 hover:bg-blue-50 hover:text-[#003d7c] hover:shadow-md border border-transparent hover:border-blue-100' }}">
                        <svg class="w-5 h-5 shrink-0" :class="sidebarCollapsed ? 'mx-auto' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        <span x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms class="font-semibold text-sm whitespace-nowrap">Kriteria Kandidat</span>
                    </a>
                </div>



            @else
                <!-- PELAMAR SIDEBAR LINKS -->
                <div class="border-b border-slate-200/50 pb-1 mb-1">
                    <a href="{{ route('pelamar.dashboard') }}" title="Dashboard User" class="flex items-center px-4 py-3 rounded-xl transition-all {{ Request::routeIs('pelamar.dashboard') ? 'bg-[#003d7c] text-white shadow-lg shadow-blue-900/20' : 'text-slate-600 hover:bg-blue-50 hover:text-[#003d7c] hover:shadow-md border border-transparent hover:border-blue-100' }}">
                        <svg class="w-5 h-5 shrink-0" :class="sidebarCollapsed ? 'mx-auto' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path></svg>
                        <span x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms class="font-semibold text-sm whitespace-nowrap">Dashboard User</span>
                    </a>
                </div>

                <div class="border-b border-slate-200/50 pb-1 mb-1">
                    <a href="{{ route('pelamar.lowongan') }}" title="Lowongan Pekerjaan" class="flex items-center px-4 py-3 rounded-xl transition-all {{ Request::routeIs('pelamar.lowongan') ? 'bg-[#003d7c] text-white shadow-lg shadow-blue-900/20' : 'text-slate-600 hover:bg-blue-50 hover:text-[#003d7c] hover:shadow-md border border-transparent hover:border-blue-100' }}">
                        <svg class="w-5 h-5 shrink-0" :class="sidebarCollapsed ? 'mx-auto' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms class="font-semibold text-sm whitespace-nowrap">Lowongan Pekerjaan</span>
                    </a>
                </div>
                <div class="border-b border-slate-200/50 pb-1 mb-1">
                    <a href="{{ route('pelamar.riwayat') }}" title="Lamaran Saya" class="flex items-center px-4 py-3 rounded-xl transition-all {{ Request::routeIs('pelamar.riwayat') ? 'bg-[#003d7c] text-white shadow-lg shadow-blue-900/20' : 'text-slate-600 hover:bg-blue-50 hover:text-[#003d7c] hover:shadow-md border border-transparent hover:border-blue-100' }}">
                        <svg class="w-5 h-5 shrink-0" :class="sidebarCollapsed ? 'mx-auto' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        <span x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms class="font-semibold text-sm whitespace-nowrap">Lamaran Saya</span>
                    </a>
                </div>
            @endif
        </nav>

        <!-- Sidebar Footer Controls -->
        <div class="p-4 border-t border-slate-200/60 shrink-0">
            <div class="border-b border-slate-200/50 pb-1 mb-1">
            
            <div class="border-b border-slate-200/50 pb-1 mb-1">
                <form action="{{ route('logout') }}" method="POST" class="block w-full" id="logout-form-desktop" @submit.prevent="$dispatch('open-confirm-modal', {
                    title: 'Yakin ingin keluar?',
                    message: 'Sesi Anda akan diakhiri dan Anda harus login kembali untuk masuk ke dashboard.',
                    confirmText: 'Ya, Logout',
                    type: 'danger',
                    actionType: 'submit',
                    formElement: document.getElementById('logout-form-desktop')
                })">
                    @csrf
                    <button type="submit" title="Keluar / Logout" class="flex items-center w-full px-4 py-3 rounded-xl text-red-500 hover:bg-red-50 hover:text-red-700 transition-all text-left">
                        <svg class="w-5 h-5 shrink-0" :class="sidebarCollapsed ? 'mx-auto' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms class="font-semibold text-sm whitespace-nowrap">Keluar / Logout</span>
                    </button>
                </form>
            </div>


        </div>
    </aside>

    <!-- Mobile Drawer Sidebar -->
    <div x-show="sidebarOpen" class="fixed inset-0 z-40 lg:hidden flex" x-cloak>
        <!-- Overlay -->
        <div @click="sidebarOpen = false" class="fixed inset-0 bg-slate-900/40 transition-opacity duration-300"></div>

        <!-- Sidebar Content -->
        <div class="relative flex-1 flex flex-col max-w-[280px] w-full bg-white border-r border-slate-200 text-slate-700 transition duration-300 transform shadow-xl"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full">
            
            <div class="absolute top-0 right-0 -mr-12 pt-2">
                <button @click="sidebarOpen = false" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none bg-white/10 text-white hover:bg-white/20 transition-colors">
                    <span class="sr-only">Close sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l18 18" /></svg>
                </button>
            </div>

            <!-- Logo Header -->
            <div class="h-20 flex items-center gap-3 px-6 border-b border-slate-200/50 shrink-0">
                <div class="bg-white p-1 rounded-lg shadow-sm border border-slate-100">
                    <img src="{{ asset('image/LOGO UCI.jpeg') }}" alt="Logo" class="w-8 h-8 object-contain rounded">
                </div>
                <div>
                    <h1 class="text-sm font-extrabold text-[#003d7c] leading-tight">PT. UCI</h1>
                    <p class="text-[10px] text-slate-500 font-semibold tracking-wider uppercase">Portal Dashboard</p>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-grow p-4 space-y-1 overflow-y-auto z-10">
                @if(Auth::user()->role === 'superadmin')
                    <!-- SUPERADMIN MOBILE SIDEBAR -->
                    <div class="border-b border-slate-200/50 pb-1 mb-1">
                        <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ Request::routeIs('superadmin.*') ? 'bg-[#003d7c] text-white shadow-lg shadow-blue-900/20' : 'text-slate-600 hover:bg-slate-50 hover:text-[#003d7c]' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="font-semibold text-sm">Kelola Akun</span>
                        </a>
                    </div>

                @elseif(Auth::user()->role === 'hrd')
                    <div class="border-b border-slate-200/50 pb-1 mb-1">
                        <a href="{{ route('hrd.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ Request::routeIs('hrd.dashboard') ? 'bg-[#003d7c] text-white shadow-lg shadow-blue-900/20' : 'text-slate-600 hover:bg-slate-50 hover:text-[#003d7c]' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path></svg>
                            <span class="font-semibold text-sm">Dashboard</span>
                        </a>
                    </div>
                    <div class="border-b border-slate-200/50 pb-1 mb-1">
                        <a href="{{ route('hrd.hiring') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ Request::routeIs('hrd.hiring') ? 'bg-[#003d7c] text-white shadow-lg shadow-blue-900/20' : 'text-slate-600 hover:bg-slate-50 hover:text-[#003d7c]' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span class="font-semibold text-sm">HIRING</span>
                        </a>
                    </div>
                    <div class="border-b border-slate-200/50 pb-1 mb-1">
                        <a href="{{ route('hrd.pelamar-aktif') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ Request::routeIs('hrd.pelamar-aktif') ? 'bg-[#003d7c] text-white shadow-lg shadow-blue-900/20' : 'text-slate-600 hover:bg-slate-50 hover:text-[#003d7c]' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <span class="font-semibold text-sm">Data Pelamar</span>
                        </a>
                    </div>
                    <div class="border-b border-slate-200/50 pb-1 mb-1">
                        <a href="{{ route('hrd.partners.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ Request::routeIs('hrd.partners.*') ? 'bg-[#003d7c] text-white shadow-lg shadow-blue-900/20' : 'text-slate-600 hover:bg-slate-50 hover:text-[#003d7c]' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="font-semibold text-sm">Data Mitra</span>
                        </a>
                    </div>

                    <div class="border-b border-slate-200/50 pb-1 mb-1">
                        <a href="{{ route('hrd.kriteria.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ Request::routeIs('hrd.kriteria.*') ? 'bg-[#003d7c] text-white shadow-lg shadow-blue-900/20' : 'text-slate-600 hover:bg-slate-50 hover:text-[#003d7c]' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            <span class="font-semibold text-sm">Kriteria Kandidat</span>
                        </a>
                    </div>


                @else
                    <div class="border-b border-slate-200/50 pb-1 mb-1">
                        <a href="{{ route('pelamar.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ Request::routeIs('pelamar.dashboard') ? 'bg-[#003d7c] text-white shadow-lg shadow-blue-900/20' : 'text-slate-600 hover:bg-slate-50 hover:text-[#003d7c]' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path></svg>
                            <span class="font-semibold text-sm">Dashboard User</span>
                        </a>
                    </div>

                    <div class="border-b border-slate-200/50 pb-1 mb-1">
                        <a href="{{ route('pelamar.lowongan') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ Request::routeIs('pelamar.lowongan') ? 'bg-[#003d7c] text-white shadow-lg shadow-blue-900/20' : 'text-slate-600 hover:bg-slate-50 hover:text-[#003d7c]' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span class="font-semibold text-sm">Lowongan Pekerjaan</span>
                        </a>
                    </div>
                    <div class="border-b border-slate-200/50 pb-1 mb-1">
                        <a href="{{ route('pelamar.riwayat') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ Request::routeIs('pelamar.riwayat') ? 'bg-[#003d7c] text-white shadow-lg shadow-blue-900/20' : 'text-slate-600 hover:bg-slate-50 hover:text-[#003d7c]' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            <span class="font-semibold text-sm">Lamaran Saya</span>
                        </a>
                    </div>
                @endif
            </nav>

            <div class="p-4 border-t border-slate-200/60 shrink-0">
                <form action="{{ route('logout') }}" method="POST" class="block w-full mt-1" id="logout-form-mobile" @submit.prevent="$dispatch('open-confirm-modal', {
                    title: 'Yakin ingin keluar?',
                    message: 'Sesi Anda akan diakhiri dan Anda harus login kembali untuk masuk ke dashboard.',
                    confirmText: 'Ya, Logout',
                    type: 'danger',
                    actionType: 'submit',
                    formElement: document.getElementById('logout-form-mobile')
                })">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 rounded-xl text-red-500 hover:bg-red-50 hover:text-red-700 transition-all text-left">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span class="font-semibold text-sm">Keluar / Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div :class="sidebarCollapsed ? 'lg:ml-[80px]' : 'lg:ml-[280px]'" class="flex-1 flex flex-col min-w-0 z-10 relative transition-all duration-300">
        <!-- Dashboard Top Navbar (Solid white background and high z-index to prevent scrolling content leakage) -->
        <header class="bg-white h-20 shadow-sm border-b border-slate-100 flex items-center justify-between px-6 md:px-8 sticky top-0 z-30">
            <!-- Mobile Menu Toggle -->
            <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-white hover:shadow-sm focus:outline-none transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
            </button>

            <!-- Dashboard Title / Current Date -->
            <div class="hidden sm:block">
                <h2 class="text-xl font-extrabold text-[#003d7c] tracking-tight">
                    @yield('dashboard-title', 'Dashboard')
                </h2>
                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mt-0.5">{{ now()->isoFormat('dddd, D MMMM YYYY') }}</p>
            </div>

            <!-- Profile Info / Navigation -->
            <div class="flex items-center gap-4 ml-auto">
                <a href="{{ url('/') }}" class="text-[11px] font-bold text-[#003d7c] bg-white border border-[#003d7c]/10 hover:bg-blue-50 px-4 py-2 rounded-xl transition-all shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Web Utama
                </a>

                @if(Auth::user()->role === 'pelamar')
                    @php
                        $notifications = Auth::user()->notifications()->take(5)->get();
                        $unreadCount = Auth::user()->notifications()->where('is_read', false)->count();
                    @endphp
                    <!-- Notification Bell Dropdown -->
                    <div class="relative" x-data="{ 
                        open: false,
                        unreadCount: {{ $unreadCount }},
                        markAsRead() {
                            if (this.unreadCount > 0) {
                                fetch('{{ route('pelamar.notifications.markRead') }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    }
                                }).then(res => {
                                    if (res.ok) {
                                        this.unreadCount = 0;
                                    }
                                });
                            }
                        }
                    }" @click.outside="open = false">
                        <!-- Bell button -->
                        <button @click="open = !open; if(open) { markAsRead(); }"
                                class="relative p-2 text-slate-500 hover:text-[#003d7c] hover:bg-slate-100/80 rounded-xl transition-all focus:outline-none">
                            <!-- Counter Badge -->
                            <template x-if="unreadCount > 0">
                                <span class="absolute top-1.5 right-1.5 flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                                </span>
                            </template>
                            <!-- Bell Icon -->
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"></path>
                            </svg>
                        </button>
                        <!-- Dropdown menu -->
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2.5 w-80 max-w-[calc(100vw-2rem)] bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-slate-100/80 z-50 overflow-hidden"
                             style="display: none;">
                            
                            <!-- Header -->
                            <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                                <span class="text-xs font-black text-slate-800">Notifikasi</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Terbaru</span>
                            </div>

                            <!-- List -->
                            <div class="max-h-64 overflow-y-auto divide-y divide-slate-50">
                                @forelse($notifications as $notif)
                                    <div class="p-4 hover:bg-slate-50/50 transition-colors text-left {{ !$notif->is_read ? 'bg-blue-50/30' : '' }}">
                                        <div class="flex items-start justify-between gap-2">
                                            <h4 class="text-xs font-bold text-slate-800 flex-grow">{{ $notif->title }}</h4>
                                            <span class="shrink-0 text-[9px] text-slate-400 font-medium">{{ $notif->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">{{ $notif->message }}</p>
                                    </div>
                                @empty
                                    <div class="p-6 text-center text-slate-400 font-semibold text-xs">
                                        Tidak ada notifikasi baru.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endif
                
                <a href="{{ Auth::user()->role === 'hrd' ? route('hrd.profil') : route('pelamar.profil') }}" class="flex items-center gap-3 pl-4 border-l border-slate-200/60 hover:opacity-80 transition-opacity cursor-pointer group">
                    @if(Auth::user()->role === 'pelamar' && Auth::user()->profile && Auth::user()->profile->photo_path)
                        <img src="{{ asset('storage/' . Auth::user()->profile->photo_path) }}" alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-full object-cover shadow-md border-2 border-white group-hover:scale-105 transition-transform">
                    @else
                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-[#003d7c] to-[#005fb8] text-white flex items-center justify-center font-bold text-sm shadow-md uppercase border-2 border-white group-hover:scale-105 transition-transform">
                            {{ substr(Auth::user()->name, 0, 2) }}
                        </div>
                    @endif
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-bold text-slate-800 leading-none mb-1 group-hover:text-[#003d7c] transition-colors">{{ Auth::user()->name }}</p>
                        <span class="text-[10px] text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md uppercase font-bold">{{ Auth::user()->role }}</span>
                    </div>
                </a>
            </div>
        </header>

        <!-- Main Workspace -->
        <main class="flex-grow p-6 md:p-8 overflow-y-auto">
            @yield('dashboard-content')
        </main>
    </div>

    <!-- Reusable Confirmation Modal -->
    <x-modal-confirm />
</div>

<style>
    /* Custom Scrollbar for Sidebar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(203, 213, 225, 0.5); /* slate-300 with opacity */
        border-radius: 4px;
    }
    .custom-scrollbar:hover::-webkit-scrollbar-thumb {
        background: rgba(148, 163, 184, 0.8); /* slate-400 with opacity */
    }
</style>
@endsection
