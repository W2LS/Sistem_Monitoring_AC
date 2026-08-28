<!-- FLOATING BOTTOM NAVIGATION BAR (PINDAD INDUSTRIAL PALETTE: #1D1616, #8E1616, #D84040, #EEEEEE) -->
<div class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 w-[92%] max-w-md">
    
    <div class="bg-[#1D1616] h-16 rounded-[32px] px-4 flex items-center justify-between shadow-[0_20px_50px_-12px_rgba(29,22,22,0.5)] border border-[#8E1616]/30 relative">
        
        <!-- 1. HOME ICON (DASHBOARD) -->
        <button 
            @click="activeTab = 'home'" 
            type="button"
            class="w-12 h-12 rounded-full flex flex-col items-center justify-center transition-all cursor-pointer group"
            :class="activeTab === 'home' ? 'text-white' : 'text-[#EEEEEE]/50 hover:text-white'">
            <svg class="w-6 h-6 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="text-[9px] font-black uppercase tracking-wider mt-0.5" :class="activeTab === 'home' ? 'text-[#D84040]' : 'text-[#EEEEEE]/50'">Home</span>
        </button>

        <!-- 2. CLOCK ICON (PENJADWALAN) -->
        <button 
            @click="activeTab = 'search'" 
            type="button"
            class="w-12 h-12 rounded-full flex flex-col items-center justify-center transition-all cursor-pointer group"
            :class="activeTab === 'search' ? 'text-white' : 'text-[#EEEEEE]/50 hover:text-white'">
            <svg class="w-6 h-6 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-[9px] font-black uppercase tracking-wider mt-0.5" :class="activeTab === 'search' ? 'text-[#D84040]' : 'text-[#EEEEEE]/50'">Jadwal</span>
        </button>

        <!-- 3. CENTER ACTION BUTTON (FAB) CUTOUT: ICON LAMPU PINTAR (#D84040 with 4px #EEEEEE border) -->
        <div class="relative -top-6 flex items-center justify-center">
            <button 
                @click="modalFabOpen = true" 
                type="button"
                title="Aksi Pintar & Kontrol Cepat"
                class="w-14 h-14 rounded-full bg-[#D84040] text-white flex items-center justify-center border-4 border-[#EEEEEE] shadow-[0_12px_28px_rgba(216,64,64,0.45)] hover:scale-110 active:scale-95 transition-all duration-200 cursor-pointer group">
                <svg class="w-7 h-7 transform group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
            </button>
        </div>

        <!-- 4. BOOK ICON (LOG TELEMETRI AC1 VS AC2) -->
        <button 
            @click="activeTab = 'book'" 
            type="button"
            class="w-12 h-12 rounded-full flex flex-col items-center justify-center transition-all cursor-pointer group"
            :class="activeTab === 'book' ? 'text-white' : 'text-[#EEEEEE]/50 hover:text-white'">
            <svg class="w-6 h-6 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <span class="text-[9px] font-black uppercase tracking-wider mt-0.5" :class="activeTab === 'book' ? 'text-[#D84040]' : 'text-[#EEEEEE]/50'">Log AC</span>
        </button>

        <!-- 5. SISTEM ICON (INFORMASI SISTEM & HARDWARE) -->
        <button 
            @click="activeTab = 'akun'" 
            type="button"
            class="w-12 h-12 rounded-full flex flex-col items-center justify-center transition-all cursor-pointer group"
            :class="activeTab === 'akun' ? 'text-white' : 'text-[#EEEEEE]/50 hover:text-white'">
            <svg class="w-6 h-6 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span class="text-[9px] font-black uppercase tracking-wider mt-0.5" :class="activeTab === 'akun' ? 'text-[#D84040]' : 'text-[#EEEEEE]/50'">Sistem</span>
        </button>

    </div>

</div>
