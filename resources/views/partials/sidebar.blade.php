<!-- VERTICAL COLLAPSIBLE SIDEBAR PARADIGM -->
<aside class="vertical-sidebar"> 
    <input type="checkbox" role="switch" id="checkbox-input" class="checkbox-input" checked />
    <nav>
        <header>
            <div class="sidebar__toggle-container"> 
                <label tabindex="0" for="checkbox-input" id="label-for-checkbox-input" class="nav__toggle"> 
                    <span class="toggle--icons" aria-hidden="true"> 
                        <svg width="24" height="24" viewBox="0 0 24 24" class="toggle-svg-icon toggle--open">
                            <path d="M3 5a1 1 0 1 0 0 2h18a1 1 0 1 0 0-2zM2 12a1 1 0 0 1 1-1h18a1 1 0 1 1 0 2H3a1 1 0 0 1-1-1M2 18a1 1 0 0 1 1-1h18a1 1 0 1 1 0 2H3a1 1 0 0 1-1-1"></path>
                        </svg> 
                        <svg width="24" height="24" viewBox="0 0 24 24" class="toggle-svg-icon toggle--close">
                            <path d="M18.707 6.707a1 1 0 0 0-1.414-1.414L12 10.586 6.707 5.293a1 1 0 0 0-1.414 1.414L10.586 12l-5.293 5.293a1 1 0 1 0 1.414 1.414L12 13.414l5.293 5.293a1 1 0 0 0 1.414-1.414L13.414 12z"></path>
                        </svg> 
                    </span> 
                </label> 
            </div>
            
            <figure> 
                <div class="w-11 h-11 rounded-2xl bg-slate-900 text-white font-black text-xl flex items-center justify-center mx-auto shadow-md font-outfit">
                    P
                </div>
                <figcaption>
                    <p class="user-id font-outfit font-black text-slate-800">PT PINDAD</p>
                    <p class="user-role font-semibold text-slate-500">IoT Monitoring AC</p>
                </figcaption>
            </figure>
        </header>

        <section class="sidebar__wrapper">
            <!-- UTAMA -->
            <ul class="sidebar__list list--primary">
                <li class="sidebar__item item--heading">
                    <h2 class="sidebar__item--heading">Utama</h2>
                </li>
                <li class="sidebar__item"> 
                    <a class="sidebar__link" href="#" data-tooltip="Dashboard"> 
                        <span class="icon"> 
                            <svg width="16" height="16" fill="currentColor" class="bi bi-grid-1x2" viewBox="0 0 16 16">
                                <path d="M6 1H1v14h5V1zm9 0h-5v5h5V1zm0 7h-5v7h5V8zM1.5 0A1.5 1.5 0 0 0 0 1.5v13A1.5 1.5 0 0 0 1.5 16h13a1.5 1.5 0 0 0 1.5-1.5v-13A1.5 1.5 0 0 0 14.5 0h-13z"/>
                            </svg> 
                        </span> 
                        <span class="text font-extrabold text-slate-800">Dashboard</span> 
                    </a> 
                </li>
                <li class="sidebar__item"> 
                    <a class="sidebar__link" href="#kontrol-ac" data-tooltip="Kontrol AC"> 
                        <span class="icon"> 
                            <svg width="16" height="16" fill="currentColor" class="bi bi-toggle-on" viewBox="0 0 16 16">
                                <path d="M5 3a5 5 0 0 0 0 10h6a5 5 0 0 0 0-10H5zm6 9a4 4 0 1 1 0-8 4 4 0 0 1 0 8z"/>
                            </svg> 
                        </span> 
                        <span class="text">Kontrol AC</span> 
                    </a> 
                </li>
                <li class="sidebar__item"> 
                    <a class="sidebar__link" href="#grafik-telemetri" data-tooltip="Grafik Arus"> 
                        <span class="icon"> 
                            <svg width="16" height="16" fill="currentColor" class="bi bi-graph-up-arrow" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M0 0h1v15h15v1H0V0zm10 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V4.9l-3.613 4.417a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.564L13.293 4H10.5a.5.5 0 0 1-.5-.5z"/>
                            </svg> 
                        </span> 
                        <span class="text">Grafik Telemetri</span> 
                    </a> 
                </li>
            </ul>

            <!-- SISTEM -->
            <ul class="sidebar__list list--secondary">
                <li class="sidebar__item item--heading">
                    <h2 class="sidebar__item--heading">Sistem</h2>
                </li>
                <li class="sidebar__item"> 
                    <a class="sidebar__link" href="#" data-tooltip="Status MQTT"> 
                        <span class="icon"> 
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse m-auto"></span>
                        </span> 
                        <span class="text font-bold text-emerald-700" id="sidebar-mqtt-label">MQTT Live</span> 
                    </a> 
                </li>
                <li class="sidebar__item"> 
                    <a class="sidebar__link" href="#" data-tooltip="Jam Server"> 
                        <span class="icon"> 
                            <svg width="16" height="16" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16">
                                <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022l-.074.997zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.122.342l-.356.933zM1.91 4.98a7.003 7.003 0 0 0-.299.985l-.976-.219c.086-.383.2-.76.342-1.122l.933.356zM8 4.5a.5.5 0 0 1 .5.5v3.086l2.146 2.147a.5.5 0 0 1-.708.708l-2.5-2.5A.5.5 0 0 1 7.5 8V5a.5.5 0 0 1 .5-.5z"/>
                            </svg> 
                        </span> 
                        <span class="text font-mono text-xs font-bold text-slate-700" id="sidebar-clock">00:00:00 WIB</span> 
                    </a> 
                </li>
                <li class="sidebar__item"> 
                    <a class="sidebar__link" href="#" data-tooltip="Pengaturan"> 
                        <span class="icon"> 
                            <svg width="16" height="16" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
                                <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0"/>
                                <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52z"/>
                            </svg> 
                        </span> 
                        <span class="text">Pengaturan</span> 
                    </a> 
                </li>
            </ul>
        </section>
    </nav>
</aside>
